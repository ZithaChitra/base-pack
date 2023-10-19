<div>

    <style>
        #draggablePanelList .panel-heading {
        cursor: move;
            }
        #draggablePanelList2 .panel-heading {
                cursor: move;
            }
    </style>
    <div class="card card-primary">
        <div class="card-header">
            <div class="row">
                <h3 class="card-title">Menu Config <span><i class="fa fa-sliders" aria-hidden="true"></i></span></h3>
            </div>
        </div>
        
        <div class="card-body">
            <div >
                <ul class="list-group">
                    <div class="row">
                        <div class="col-2"><h6>Title   </h6></div>
                        <div class="col-2"><h6>Parent  </h6></div>
                        <div class="col-2"><h6>Enabled </h6></div>
                        <div class="col-2"><h6>Visible </h6></div>
                        <div class="col-2"><h6>Access  </h6></div>
                        <div class="col-2"><h6>Action  </h6></div>
                    </div>
                    @livewire('basepack::components.nav-list', ['links' => $links], key(rand()))
                </ul>
            </div>
        </div>
    </div>
</div>