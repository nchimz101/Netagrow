<div class="card mb-1" >
    <!-- Card body -->
    <div class="card-body" style="padding:0.5rem;">
        <div  onclick="showNote({{$index}})" class="row" style="margin-left: 0px">
            @if (strlen($item->image)>0)
                <div>
                    <img src="{{$item->image}}" class="fieldImage" />
                </div>
            @endif
           
            <div class="col">
                <h4 class="card-title mb-0">{{$item->title}}</h4>
                <span class="h5 text-muted font-weight-bold mb-0">{{$item->notestatus->title}}</span><br />
                @if($item->field)
                    <span class="h5 text-muted font-weight-bold mb-0">{{$item->field->name}} - {{$item->field->crop->name}}</span>
                @endif
            </div>


            <div>
                <div class="dropdown" style="position: relative;">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v mt-1"></i>
                    </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                  <a class="dropdown-item" href="{{ route('notes.remove',['note'=>$item->id])}}">{{ __('Remove') }}</a>
                </div>
              </div>
            </div>


            
        </div>

    </div>
</div>