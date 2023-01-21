<div class="card shadow mt-3 mb-3" id="notesWidget">
    <div class="card-header">
        <div class="row">
            <div class="col-6">
                <h3 class="mb-0">{{ __('Notes')}}</h3>
            </div>
            <div class="col-6 text-right">
                <a :href="'/notes/edit/'+id" type="button" class="btn btn-primary btn-sm">{{ __('Edit') }}</a>

                <a target="_blank" v-if="is_public==1" :href="'/publicnote/'+uuid" type="button" class="btn btn-secondary btn-sm">{{ __('Public comments') }}</a>
            </div>
        </div>
    </div>
    <div class="card-body ">

        
        <div class="row">
            <div class="col-12">
                <h3>@{{ title }} - @{{ type }}</h3>
            </div>
        </div>
        <div class="row">
            <div v-if="image" class="col-4">

                <img :src="image" class="w-100" />
                <br />
             
                    

            </div>
            <div class="col-8">
                <div style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 3px;
                    height: 100%;
                    content: '';
                    border-radius: 8px;
                    background-color: #5e72e4;
                "></div>

                <p>@{{ description }}</p>

                <div class="row mt-3">
                    <div class="col-4">
                        <small class="text-muted">{{ __('Status')}}</small>
                        <h5 class="mb-0">@{{ status }}</h5>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">{{ __('Created by')}}</small>
                        <h5 class="mb-0">@{{ by }}</h5>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">{{ __('Assigned to')}}</small>
                        <h5 class="mb-0">@{{ assignedto }}</h5>
                    </div>
                <div>

                

            </div>
        </div>
    </div>
</div>