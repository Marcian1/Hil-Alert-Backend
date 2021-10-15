<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHilEntryRequest;
use Illuminate\Http\Request;
use App\Http\Resources\HilEntryResource;
use App\Hil;
use App\HilEntry;
use App\Events\PropertyChanged;
use App\User;




class HilEntryController extends Controller
{
    public function store(StoreHilEntryRequest $request, Hil $hil)
    {

        $hilEntry = new HilEntry;

        $hilEntry->machinename = $request->machinename;
        $hilEntry->osversion = $request->osversion;
        $hilEntry->projectname = $request->projectname;
        $hilEntry->selectedServers = $request->selectedServers;
        $hilEntry->labcarType = $request->labcarType;
        $hilEntry->autorun = $request->autorun;

        $secondLast = $hil->hilentries()->orderBy('created_at', 'desc')->first();
        $hil->hilentries()->save($hilEntry);

        $last = $hil->hilentries()->orderBy('created_at', 'desc')->first();

        $notificationProperties = array();

        $users = $hil->users;
        $users->each(function ($user) use (&$last, &$secondLast, &$hil, &$notificationProperties) {
            $properties = $user->properties;
            $propertiesName = $properties->pluck('name');
            $propertiesName->each(function ($property) use (&$last, &$secondLast, &$hil, &$user, &$notificationProperties) {
                if ($last->$property != $secondLast->$property) {
                    // array_push($notificationProperties, $last->$property);
                    array_push($notificationProperties, $property);
                }
            });
            if (!empty($notificationProperties)) {
                event(new PropertyChanged($user->username, $notificationProperties, $hil->labcarname));
            } else {
                $notificationProperties = array();
            }
        });

        return  new HilEntryResource($hilEntry);
    }
    public function index()
    {
        return HilEntryResource::collection(HilEntry::all());
    }
}
