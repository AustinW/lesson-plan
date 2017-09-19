<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PlanRequest;
use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return $request->user()->plans()->with('sections')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        return $request->user()->plans()->with('sections')->where('id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlanRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanRequest $request, $id)
    {
        $plan = $request->user()->plans()->with('sections')->where('id', $id)->first();

        $plan->title = $request->get('title');
        $plan->description= $request->get('description');
        $plan->date = $request->get('date');
        $plan->group = $request->get('group');

        $sectionCollection = collect($request->get('sections'));

        // Loop through existing sections
        $plan->sections->each(function($section) use($request, $sectionCollection) {

            // Delete any that don't exist on the incoming payload
            if (!in_array($section->id, $sectionCollection->pluck('id')->toArray())) {
                $section->delete();
            } else {
                // Update existing sections
                $sectionData = $sectionCollection->whereStrict('id', $section->id)->first();
                $section->title = $sectionData['title'];
                $section->event = $sectionData['event'];
                $section->content = $sectionData['content'];
                $section->save();
            }
        });

        $sectionCollection->each(function($sectionData) use($plan, $request) {
            if (!isset($sectionData['id'])) {
                $section = new Section([
                    'user_id' => $request->user()->id,
                    'title' => $sectionData['title'],
                    'event' => $sectionData['event'],
                    'content' => $sectionData['content']
                ]);
                $plan->sections()->save($section);
            }
        });

        return $plan;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
