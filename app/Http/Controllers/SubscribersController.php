<?php

namespace App\Http\Controllers;

use App\Actions\SaveSubscriber;
use App\Http\Filters\SearchSubscribersFilter;
use App\Http\Filters\SubscribersByStateFilter;
use App\Http\Requests\StoreSubscriber;
use App\Http\Requests\UpdateSubscriber;
use App\Http\Resources\SubscriberResource;
use App\Subscriber;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = QueryBuilder::for(Subscriber::class)
            ->with('fields')
            ->allowedSorts('id', 'name', 'email')
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchSubscribersFilter)->ignore(null, 'null'),
                AllowedFilter::custom('state', new SubscribersByStateFilter)->ignore(null, 'null'),
            ])
            ->paginate(request()->get('perPage', 15));

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubscriber  $request
     * @param  \App\Actions\SaveSubscriber  $saveSubscriber
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriber $request, SaveSubscriber $saveSubscriber)
    {
        $subscriber = new Subscriber();
        $saveSubscriber->execute($request, $subscriber);

        return new SubscriberResource($subscriber);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        return new SubscriberResource($subscriber);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubscriber  $request
     * @param  \App\Actions\SaveSubscriber  $saveSubscriber
     * @param  \App\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubscriber $request, SaveSubscriber $saveSubscriber, Subscriber $subscriber)
    {
        $saveSubscriber->execute($request, $subscriber);

        return new SubscriberResource($subscriber);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        // Remove all fields
        $subscriber->fields()->detach();

        $subscriber->delete();

        return response()->json(null);
    }
}
