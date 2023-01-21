<div class="card mb-1" >
    <!-- Card body -->
    <div class="card-body" style="padding:0.5rem;">
        <div class="row" style="margin-left: 0px">
            <div onclick="showItem({{$index}})">
                <img src="{{$item->image}}" class="fieldImage" />
            </div>
            <div class="col">
                <h4 class="card-title mb-0">{{$item->name}} {{$item->area_ha}}</h4>
                <span class="h5 text-muted font-weight-bold mb-0">{{$item->crop->name}}</span>
            </div>


            <div>
                <div class="dropdown" style="position: relative;">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v mt-1"></i>
                    </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                  <a class="dropdown-item" href="{{ route('fields.remove',['field'=>$item->id])}}">{{ __('Remove') }}</a>
                </div>
              </div>
            </div>


            
        </div>

    </div>
</div>