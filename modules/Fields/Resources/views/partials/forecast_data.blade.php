<div class="table-responsive">




    <div>
        <table class="table align-items-center">
            
            <tbody class="list" style="background-color: #fff">
                
                <tr>
                    <td v-for="item in forecast" :key="item.dt">
                        @include("fields::partials.weather_from_to")
                    </td>
                </tr>
                
                
                
            </tbody>
        </table>
    </div>
    
    </div>