<?php

namespace Modules\Fields\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Fields\Models\Crop;
use Modules\Fields\Models\Field;
use Image;
use Illuminate\Support\Str;
use MercadoPago\Config\Json;
use Modules\Fields\Models\Fieldnote;

class Main extends Controller
{
    /**
     * Provide class.
     */
    private $provider = Field::class;

    /**
     * Web RoutePath for the name of the routes.
     */
    private $webroute_path = 'fields.';

    /**
     * View path.
     */
    private $view_path = 'fields::';

    /**
     * Parameter name.
     */
    private $parameter_name = 'table';

    /**
     * Title of this crud.
     */
    private $title = 'Fields';

    /**
     * Title of this crud in plural.
     */
    private $titlePlural = 'Fields';

    protected $imagePath = '/uploads/settings/';

    /**
     * Auth checker functin for the crud.
     */
    private function authChecker()
    {
        if (!auth()->user()->hasRole('owner')&&!auth()->user()->hasRole('staff')) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function getFields()
    {
        return [
            ['class'=>'col-md-4', 'ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>'First and Last name', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'input', 'name'=>'Email', 'id'=>'email', 'placeholder'=>'Enter email', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'input','type'=>"password", 'name'=>'Password', 'id'=>'password', 'placeholder'=>'Enter password', 'required'=>true],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authChecker();
        $fields=$this->getFields();
        

        $items=$this->provider::where('company_id',$this->getRestaurant()->id)->get();
        if(isset($_GET['page'])&&$_GET['page']=="notes"){
            if(auth()->user()->hasRole('owner')){
                //Owner
                $items=Fieldnote::where('company_id',$this->getRestaurant()->id)->with('notetype','notestatus','field','by','for')->orderBy('id','desc')->get();
            }else{
                //Staff
                $items=Fieldnote::where('assigned_to',auth()->user()->id)->where('company_id',$this->getRestaurant()->id)->with('notetype','notestatus','field','by','for')->orderBy('id','desc')->get();
            }
            
        }

        $vendor=$this->getRestaurant();

        $setup=[
            'title'=>__($this->titlePlural),
            'items'=>$items,
            'itemsJson'=>$items->toJSON(),
            'action_link'=>"enterAddingMode()",
            'action_name'=>__('Add new field'),
            'type'=>'fields',
            'lat'=>strlen($vendor->lat."")>1?$vendor->lat:54.5260,
            'lng'=>strlen($vendor->lng."")>1?$vendor->lng:15.2551,

        ];

        if(isset($_GET['id'])){
            $selected=$this->provider::findOrFail($_GET['id']);
            if($selected->company_id!=$vendor->id){
                abort(404);
            }
            $setup['id']=$_GET['id'];
        }else{
            $setup['id']=null;
        }

        if(isset($_GET['page'])){
            unset($setup['action_link']);
            unset($setup['action_name']);
            if($_GET['page']=="weather"){
                $setup['type']="weather";
            }
            if($_GET['page']=="notes"){
                $setup['type']="notes";
                $setup['title']=__('Notes');
                $setup['action_link']="enterAddingNoteMode()";
                $setup['action_name']= __('Add new note');
            }
        }
        return view($this->view_path.'index', [
            'setup' => $setup,
            'crops'=>Crop::pluck('name', 'id')
        ]);
    }

    public function imageOfField(Field $field)
    {

        $uuid = Str::uuid()->toString();
        $img = Image::make($field->image);
        $img->save(public_path($this->imagePath,).$uuid.'.png');

        dd($img);
        return response()->file($field->image);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('fields::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        $request->merge(['company_id' => $this->getRestaurant()->id]);
        if(!$request->has('crop_id')){
            $request->merge(['crop_id' => 1]);
        }
       

        $googleImage="https://maps.googleapis.com/maps/api/staticmap?&scale=2&size=100x100&maptype=satellite&key=".config('settings.google_maps_api_key');
        
        $googleImage.="&path=color:0xffe600|weight:4";


        $cordDecoded=json_decode($request->coordinates);

        foreach ($cordDecoded[0] as $key => $cord) {
            $googleImage.="|".$cord[1].",".$cord[0];
        }
       
        $uuidImage = Str::uuid()->toString().".png";
        $img = Image::make($googleImage);
        $img->save(public_path($this->imagePath).$uuidImage);
        $request->merge(['image' => $this->imagePath.$uuidImage]);


        $field = Field::create($request->all());
       

       
       
       

        return redirect(route('fields.index'))->withSuccess(__('Item created'));
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('fields::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('fields::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function remove(Field $field)
    {
       if($field->company_id==$this->getRestaurant()->id){
            $field->delete();
            return redirect(route('fields.index'))->withStatus(__('Item removed'));
       }else{
           abort(404);
       }
    }

    public function noteforfield(Field $field){
        
        if($field->company_id==$this->getRestaurant()->id){
            $result=$field->notes()->first();
            if($result){
                return response()->json(['status'=>'ok','result'=>$result->toArray()]);
            }else{
                return response()->json(['status'=>'no','message'=>__('No items')]);
            }
            
       }else{
        return response()->json(['status'=>'no','message'=>__('Not Found')]);
       }
        
    }
}
