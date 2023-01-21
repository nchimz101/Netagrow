<?php

namespace Modules\Notes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Fields\Models\Field;
use Modules\Fields\Models\Fieldnote;
use App\Models\Posts;
use Modules\Fields\Models\Notestatus;
use Illuminate\Support\Str;

class Main extends Controller
{
    private function getFields()
    {
        $vendor=$this->getRestaurant();
        return [
            ['class'=>'col-md-6', 'ftype'=>'image', 'name'=>__('Image'), 'id'=>'imageraw'],
            ['class'=>'col-md-6', 'ftype'=>'input', 'name'=>'Title', 'id'=>'title', 'placeholder'=>'Title', 'required'=>true],
            ['class'=>'col-md-6', 'ftype'=>'input', 'name'=>'Description', 'id'=>'description', 'placeholder'=>'Description', 'required'=>true],
            
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Note type', 'id'=>'notetype_id', 'placeholder'=>'Note type', 'data'=>Posts::where('post_type','notetype')->pluck('title','id'), 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Note status', 'id'=>'notestatus_id', 'placeholder'=>'Note status', 'data'=>Posts::where('post_type','notestatus')->pluck('title','id'), 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Related field', 'id'=>'field_id', 'placeholder'=>'Field', 'data'=>Field::where('company_id',$this->getRestaurant()->id)->pluck('name','id'), 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Assigned to', 'id'=>'assigned_to', 'placeholder'=>'Assigned to', 'data'=>$vendor->staff->pluck('name','id'), 'required'=>true],
            ['class'=>'col-md-6', 'ftype'=>'map', 'name'=>'Location', 'id'=>'location', 'placeholder'=>'Location','lat'=>strlen($vendor->lat."")>1?$vendor->lat:54.5260,'lng'=>strlen($vendor->lng."")>1?$vendor->lng:54.5260],
            ['class'=>'col-md-4', 'ftype'=>'bool', 'name'=>"Public",'id'=>"is_public",'value'=>"false"],
            
            /*['ftype'=>'image', 'name'=>__('City image ( 200x200 )'), 'id'=>'image_up'],
            ['ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>'Enter city name', 'required'=>true],
            ['ftype'=>'input', 'name'=>'City 2 - 3 letter short code', 'id'=>'alias', 'placeholder'=>'Enter city short code ex. ny', 'required'=>true],
            ['ftype'=>'input', 'name'=>'Header title', 'id'=>'header_title', 'placeholder'=>'Header title', 'required'=>true],
            ['ftype'=>'input', 'name'=>'Header subtitle', 'id'=>'header_subtitle', 'placeholder'=>'Header subtitle', 'required'=>true],*/

        ];
    }

    public function new()
    {
        return view('general.form', ['setup' => [
            'title'=>'New note',
            'action_link'=>route('fields.index',['page'=>'notes']),
            'action_name'=>__('Back'),
            'iscontent'=>true,
            'action'=>route('notes.store'),
            'breadcrumbs'=>[
                [__('Notes'), route('fields.index',['page'=>'notes'])],
                [__('New'), null],
            ],
        ],
        'fields'=>$this->getFields(), ]);
    }

   

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('notes::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('notes::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'notetype_id' => ['required'],
            'notestatus_id' => ['required'],
        ]);
        $data=$request->all();
        $data['company_id']=$this->getRestaurant()->id;
        $data['created_by']=auth()->user()->id;
        $data['uuid']=Str::uuid()->toString();


        if ($request->hasFile('imageraw')) {
            $data['image'] = $this->saveImageVersions(
                'uploads/restorants/',
                $request->imageraw,
                [
                    ['name'=>'large'],
                ]
            );
            unset($data['imageraw']);
        }

        if($request->has('is_public')){
            $data['is_public'] =1;
        }
        
        $fieldNote=Fieldnote::create($data);

        if ($request->hasFile('imageraw')) {
            $fieldNote->image=$fieldNote->image_link;
            $fieldNote->update();
        }
        
       
       

        return redirect(route('fields.index',['page'=>'notes']));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($note)
    {
        $note=Fieldnote::where('uuid',$note)->where('is_public',1)->firstOrFail();
        return view('agrislanding.note',['note'=>$note]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Fieldnote $note)
    {
        //dd($note);
        $fields=$this->getFields();
        $fields[0]['value']=$note->image;
        $fields[1]['value']=$note->title;
        $fields[2]['value']=$note->description;
        $fields[3]['value']=$note->notetype_id;
        $fields[4]['value']=$note->notestatus_id;
        $fields[5]['value']=$note->field_id;
        $fields[6]['value']=$note->assigned_to;

        $fields[7]['lat']=$note->lat;
        $fields[7]['lng']=$note->lng;

        $fields[8]['value']=$note->is_public.""=="1"?true:false;

        return view('general.form', ['setup' => [
            'isupdate'=>true,
            'title'=>__('Edit')." ".$note->title,
            'action_link'=>route('fields.index',['page'=>'notes']),
            'action_name'=>__('Back'),
            'iscontent'=>true,
            'action'=>route('notes.update',['note'=>$note->id]),
            'breadcrumbs'=>[
                [__('Notes'), route('fields.index',['page'=>'notes'])],
                [__('Edit'), null],
            ],
        ],
        'fields'=>$fields, ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'notetype_id' => ['required'],
            'notestatus_id' => ['required'],
        ]);
        
        $item = Fieldnote::findOrFail($id);

        if ($request->hasFile('imageraw')) {
            $item->image = $this->saveImageVersions(
                'uploads/restorants/',
                $request->imageraw,
                [
                    ['name'=>'large'],
                ]
            );
        }

        $item->title = $request->title;
        $item->description = $request->description;
        $item->notetype_id = $request->notetype_id;
        $item->notestatus_id = $request->notestatus_id;
        $item->field_id = $request->field_id;
        $item->assigned_to = $request->assigned_to;
        $item->lat = $request->lat;
        $item->lng = $request->lng;

       // dd($request->all());

        if($request->has('is_public')){
            $item->is_public = 1;
        }else{
            $item->is_public = 0;
        }

        $item->update();

        if ($request->hasFile('imageraw')) {
            $item->image=$item->image_link;
            $item->update();
        }

        return redirect()->route('fields.index',['page'=>"notes"])->withStatus(__('crud.item_has_been_updated', ['item'=>__($item->title)]));
    
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function remove(Fieldnote $note)
    {
        
        if($note->company_id==$this->getRestaurant()->id){
            $note->delete();
            return redirect(route('fields.index',['page'=>"notes"]))->withStatus(__('Item removed'));
       }else{
           abort(404);
       }
    }
}
