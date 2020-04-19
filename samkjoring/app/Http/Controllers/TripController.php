<?php

namespace App\Http\Controllers;

use DB;
use App\Trip;
use App\Passenger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$trips = Trip::latest()->get();
        // DB::table('trips')->get();
        $trips = DB::select('select * from trips order by id desc limit 1');
        return view('home',['trips'=>$trips]);
        //return view('home', [
        //  'home' => $trips
        //]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log Trip store
        $logString = 'Ny tur: ' . request('start_point') . ' - ' . request('end_point') .
                     ' , Start: ' . request('start_date') . ' ' . request('start_time') .
                     ' , End: ' . request('end_date') . ' ' . request('end_time') .
                     ' , Bruker ID' . request('driver_id');
        Log::channel('samkjøring')->info($logString);

        $validatedResults = request()->validate([
          'driver_id' => ['required'],
          'start_point' => ['required', 'string', 'max:255'],
          'end_point' => ['required', 'string', 'max:255'],
          'start_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
          'start_time' => ['required', 'date_format:H:i'], //må ha date_format på tid!!!!!!!!!!!!!!!!!!!
          'end_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
          'end_time' => ['required', 'date_format:H:i'],
          'seats_available' => ['required', 'digits_between:1,45'],
          'car_description' => ['required', 'string', 'max:255'],
          'trip_info' => ['required', 'string'],
          'pets_allowed' => ['required', 'boolean'],
          'kids_allowed' => ['required', 'boolean'],
          'trip_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        // Tur Bilde Opplastning
        if ($files = $request->file('trip_image')) {
          $destinationPath = 'tripImage/'; // upload path
          $profileImage = 'image'. '_' . date('YmdHis') . "." . $files->getClientOriginalExtension();
          $files->move($destinationPath, $profileImage);
          //$insert['trip_image'] = "$profileImage";
          $validatedResults['trip_image'] = "$profileImage";
        }
        //$check = Trip::insertGetId($insert);
        Trip::create($validatedResults);
        //Trip::create($this->validateTrip());
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        return view('trips.show', ['trip' => $trip]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Trip $trip)
    {
        // Her skal det jobbes du! :P
        return view('trips.edit', ['trip' => $trip]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip) //
    {
        //
        //$trip->update($this->validateTrip());
        //return view('trip.show', ['trip' => $trip]);

        // Log Trip oppdatering oppdatering
        $logString = 'Oppdatert tur: ' . request('start_point') . ' - ' . request('end_point') .
                     ' , Ny Start: ' . request('start_date') . ' ' . request('start_time') .
                     ' , Ny End: ' . request('end_date') . ' ' . request('end_time') .
                     ' , Bruker ID' . ' ' . request('driver_id');
        Log::channel('samkjøring')->info($logString);

        $validatedResults = request()->validate([
          'driver_id' => ['required'],
          'start_point' => ['required', 'string', 'max:255'],
          'end_point' => ['required', 'string', 'max:255'],
          'start_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
          'start_time' => ['required', 'date_format:H:i'], //må ha date_format på tid!!!!!!!!!!!!!!!!!!!
          'end_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
          'end_time' => ['required', 'date_format:H:i'],
          'seats_available' => ['required', 'digits_between:1,45'],
          'car_description' => ['required', 'string', 'max:255'],
          'trip_info' => ['required', 'string'],
          'pets_allowed' => ['required', 'boolean'],
          'kids_allowed' => ['required', 'boolean'],
          'trip_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        if ($files = $request->file('trip_image')) {
          $destinationPath = 'tripImage/'; // upload path
          $profileImage = 'image'. '_' . date('YmdHis') . "." . $files->getClientOriginalExtension();
          $files->move($destinationPath, $profileImage);
          //$insert['trip_image'] = "$profileImage";
          $validatedResults['trip_image'] = "$profileImage";
        }

        $trip->update($validatedResults);

        $trips = DB::table('trips')->whereRaw('id = ' . $trip->id)->get();
        //foreach ($trips as $trup) { //kanskje trips[0]->id osv??
        $trip = $trips[0];
        /*
          $trip->id = $trup->id;
          $trip->driver_id = $trup->driver_id;
          $trip->start_point = $trup->start_point;
          $trip->end_point = $trup->end_point;
          $trip->start_date = $trup->start_date;
          $trip->start_time = $trup->start_time;
          $trip->end_date = $trup->end_date;
          $trip->end_time = $trup->end_time;
          $trip->seats_available = $trup->seats_available;
          $trip->car_description = $trup->car_description;
          $trip->trip_info = $trup->trip_info;
          $trip->pets_allowed = $trup->pets_allowed;
          $trip->kids_allowed = $trup->kids_allowed;*/
      //}

        //return redirect('/trips/' . $trip->id . '/seemore/'); // Dette er hvor du blir sendt etter å ha postet!

        $users = DB::select('select users.firstname, users.lastname, users.id, passengers.seats_requested from users, trips, passengers where passengers.trip_id = ' . $trip->id . ' and passenger_id = users.id and trips.id = ' . $trip->id);
        $piss = 0;
        $chauffeur = DB::select('select * from users where users.id = ' . $trip->driver_id);
        //dd($trip);
        //return view('trips.seemore', ['trip' => $trip, 'users' => $users, 'piss' => $piss, 'chauffeur' => $chauffeur]);
        //return redirect()->action('TripController@seemore', ['trip' => $trip, 'users' => $users, 'piss' => $piss, 'chauffeur' => $chauffeur]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
      // Log at en tur er kansellert av bruker
      $logString = 'Tur deaktivert: ' . $trip->id . ' ' .$trip->start_point . ' - ' . $trip->end_point . ' av brukerID: ' . $trip->driver_id;
      Log::channel('samkjøring')->info($logString);



        // setta turen til deaktiv
        // kjør ned kaffe?
        //Trip::update()
        // fjerna passasjer???
        $trip->trip_active = false;
        $trip->save();

        //return redirect('/');

        $trips = DB::table('trips')->whereRaw('id = ' . $trip->id)->get();
        $trip = $trips[0];

        return redirect()->action('NotificationController@store', ['trip' => $trip]);
        //return redirect(session('links')[2]); // Denne her vil sende deg 2 linker tilbake
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function seeMore(Trip $trip)
    {
        $users = DB::select('select users.firstname, users.lastname, users.id, passengers.seats_requested from users, trips, passengers where passengers.trip_id = ' . $trip->id . ' and passenger_id = users.id and trips.id = ' . $trip->id);
        $piss = 0;
        $chauffeur = DB::select('select * from users where users.id = ' . $trip->driver_id);
        return view('trips.seemore', ['trip' => $trip, 'users' => $users, 'piss' => $piss, 'chauffeur' => $chauffeur]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function join(Request $request, Trip $trip) //
    {
        //
        //$trip->update($this->validateTrip());
        //return view('trip.show', ['trip' => $trip]);

        // Log Trip edit oppdatering
        /*$logString = 'Endra tur: ' . request('start_point') . ' - ' . request('end_point') .
                     ' , Ny Start: ' . request('start_date') . ' ' . request('start_time') .
                     ' , Ny End: ' . request('end_date') . ' ' . request('end_time') .
                     ' , Bruker ID' . ' ' . request('driver_id');
        Log::channel('samkjøring')->info($logString);*/

        /*$requestData = request()->all();
        $requestData['seats_available'] = $trip->seats_available - request('seats_available');*/
        //request()->replace('seats_available', $trip->seats_available - request('seats_available'));
        //$pass = new \Passenger(request('trip_id'), request('passenger_id'), request('seats_available'));
        $request->request->add(['seats_requested' => request('seats_available')]); //legge te seats_requested, DATABASEN LIKA IKKJE Å IKKJE FÅ INN ALLE FELTI MED RETT NAVN
        $validatedPassenger = request()->validate([
          'trip_id' => ['required', 'exists:trips,id'],
          'passenger_id' => ['required', 'exists:users,id'],
          'seats_requested' => ['required', 'digits_between:1,45'],
        ]);

        Passenger::create($validatedPassenger);

        request()->merge([ 'seats_available' => $trip->seats_available - request('seats_available') ]);

        $validatedResults = request()->validate([
          'seats_available' => ['required', 'digits_between:1,45'],
        ]);

        //dd(request('seats_available'));

        $trip->update($validatedResults);

        //dd($trip);

        //$trip->update($this->validateSeats());
        //dd($request);

        //return redirect('/');
        //return view('/', $request); // Dette er hvor du blir sendt etter å ha postet!
        //return view('trips.seemore', ['trip' => $trip]);
        $users = DB::select('select users.firstname, users.lastname, users.id, passengers.seats_requested from users, trips, passengers where passengers.trip_id = ' . $trip->id . ' and passenger_id = users.id and trips.id = ' . $trip->id);
        $piss = 0;
        $chauffeur = DB::select('select * from users where users.id = ' . $trip->driver_id);
        //return view('trips.seemore', ['trip' => $trip, 'users' => $users, 'piss' => $piss, 'chauffeur' => $chauffeur]);
        //return redirect()->action('TripController@seemore', ['trip' => $trip, 'users' => $users, 'piss' => $piss, 'chauffeur' => $chauffeur]);
        return redirect()->back();
    }


    public function myTrips()
    {
      // Get the currently authenticated user...
      $user = Auth::user();
      //dd($user);

        //return view('home');
        // auth()->user()->id;
        //$user = auth()->user(); // Kan vi bruke denne nedenfor??
        $id = $user->id; // slik??
        //$id = auth()->user()->id;
        //  and (start_date < CURDATE and start_time < CURTIME or start_date > CURDATE)
        $trips = DB::select('select * from trips where driver_id = ' . $id . ' and (start_date >= CURDATE() and start_time >= CURTIME() or start_date > CURDATE()) order by trip_active desc, start_date, start_time desc');

        // Log bruker login
        // Bør kanskje lage kortere log: "Login bruker: 5. Kari Nord"
        // Log::channel('samkjøring')->info('Login bruker: ' . $id . '. ' . $user->firstname . ' ' . $user->lastname);


        return view('profile/myTrips', ['trips'=>$trips]);
    }

    public function myJoinedTrips()
    {
      // Get the currently authenticated user...
      $user = Auth::user();
      //dd($user);

        //return view('home');
        // auth()->user()->id;
        //$user = auth()->user(); // Kan vi bruke denne nedenfor??
        $id = $user->id; // slik??
        //$id = auth()->user()->id;
        //  and (start_date < CURDATE and start_time < CURTIME or start_date > CURDATE)
        $trips = DB::select('select * from trips, passengers where passenger_id = ' . $id . ' and trip_id = trips.id and (start_date >= CURDATE() and start_time >= CURTIME() or start_date > CURDATE()) order by trip_active desc, start_date, start_time desc');

        // Log bruker login
        // Bør kanskje lage kortere log: "Login bruker: 5. Kari Nord"
        // Log::channel('samkjøring')->info('Login bruker: ' . $id . '. ' . $user->firstname . ' ' . $user->lastname);


        return view('profile/myJoinedTrips', ['trips'=>$trips]);
    }

    protected function validateTrip()
    {
      return request()->validate([
        'driver_id' => ['required'],
        'start_point' => ['required', 'string', 'max:255'],
        'end_point' => ['required', 'string', 'max:255'],
        'start_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
        //'start_time' => ['required', 'date', 'after_or_equal:' . date('h:i')],
        'start_time' => ['required', 'date_format:H:i'], //må ha date_format på tid!!!!!!!!!!!!!!!!!!!
        'end_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
        //'end_time' => ['required', 'date', 'after_or_equal:' . date('h:i')],
        'end_time' => ['required', 'date_format:H:i'],
        'seats_available' => ['required', 'digits_between:1,45'],
        'car_description' => ['required', 'string', 'max:255'],
        'trip_info' => ['required', 'string'],
        'pets_allowed' => ['required', 'boolean'],
        'kids_allowed' => ['required', 'boolean'],
        'trip_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
      ]);
    }

    protected function validatePassenger()
    {
      return request()->validate([
        'trip_id' => ['required', 'exists:trips,id'],
        'passenger_id' => ['required', 'exists:users,id'],
        'seats_requested' => ['required', 'digits_between:1,45'],
      ]);
    }

    protected function validateSeats()
    {
      return request()->validate([
        'seats_available' => ['required', 'digits_between:1,45'],
      ]);
    }
}
