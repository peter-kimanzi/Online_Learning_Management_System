<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Move stuff between containers</h4>
                <p class="text-muted font-14 mb-3">
                    Just specify the data attribute
                    <code>data-plugin='dragula'</code> and
                    <code>data-containers='["first-container-id", "second-container-id"]'</code>.
                </p>

                <div class="row" data-plugin="dragula" data-containers='["company-list-left", "company-list-right"]'>
                    <div class="col-md-6">
                        <div class="bg-dragula p-2 p-lg-4">
                            <h5 class="mt-0">Part 1</h5>
                            <div id="company-list-left" class="py-2">

                                <div class="card mb-0 mt-2 draggable-item" id = "1">
                                    <div class="card-body">
                                        <div class="media">
                                            <img src="<?php echo base_url('uploads/user_image/1.jpg'); ?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
                                            <div class="media-body">
                                                <h5 class="mb-1 mt-0">Louis K. Bond</h5>
                                                <p> Founder & CEO </p>
                                                <p class="mb-0 text-muted">
                                                    <span class="font-italic"><b>"</b>Disrupt pork belly poutine, asymmetrical tousled succulents selfies. You probably haven't heard of them tattooed master cleanse live-edge keffiyeh.</span>
                                                </p>
                                            </div> <!-- end media-body -->
                                        </div> <!-- end media -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end col -->

                                <div class="card mb-0 mt-2 draggable-item" id = "2">
                                    <div class="card-body">
                                        <div class="media">
                                            <img src="<?php echo base_url('uploads/user_image/1.jpg'); ?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
                                            <div class="media-body">
                                                <h5 class="mb-1 mt-0">Dennis N. Cloutier</h5>
                                                <p> Software Engineer </p>
                                                <p class="mb-0 text-muted">
                                                    <span class="font-italic"><b>"</b>Disrupt pork belly poutine, asymmetrical tousled succulents selfies. You probably haven't heard of them tattooed master cleanse live-edge keffiyeh.</span>
                                                </p>
                                            </div> <!-- end media-body -->
                                        </div> <!-- end media -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end col -->

                                <div class="card mb-0 mt-2 draggable-item" id = "3">
                                    <div class="card-body">
                                        <div class="media">
                                            <img src="<?php echo base_url('uploads/user_image/1.jpg'); ?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
                                            <div class="media-body">
                                                <h5 class="mb-1 mt-0">Susan J. Sander</h5>
                                                <p> Web Designer </p>
                                                <p class="mb-0 text-muted">
                                                    <span class="font-italic"><b>"</b>Disrupt pork belly poutine, asymmetrical tousled succulents selfies. You probably haven't heard of them tattooed master cleanse live-edge keffiyeh.</span>
                                                </p>
                                            </div> <!-- end media-body -->
                                        </div> <!-- end media -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end col -->

                            </div> <!-- end company-list-1-->
                        </div> <!-- end div.bg-light-->
                    </div> <!-- end col -->

                    <div class="col-md-6">
                        <div class="bg-dragula p-2 p-lg-4">
                            <h5 class="mt-0">Part 2</h5>
                            <div id="company-list-right" class="py-2">
                                <div class="card mb-0 mt-2 draggable-item" id="4">

                                    <div class="card-body p-3">
                                        <div class="media">
                                            <img src="<?php echo base_url('uploads/user_image/1.jpg'); ?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
                                            <div class="media-body">
                                                <h5 class="mb-1 mt-0">James M. Short</h5>
                                                <p> Web Developer </p>
                                                <p class="mb-0 text-muted">
                                                    <span class="font-italic"><b>"</b>Disrupt pork belly poutine, asymmetrical tousled succulents selfies. You probably haven't heard of them tattooed master cleanse live-edge keffiyeh </span>
                                                </p>
                                            </div> <!-- end media-body -->
                                        </div> <!-- end media -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end col -->

                                <div class="card mb-0 mt-2 draggable-item" id="5">
                                    <div class="card-body p-3">
                                        <div class="media">
                                            <img src="<?php echo base_url('uploads/user_image/1.jpg'); ?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
                                            <div class="media-body">
                                                <h5 class="mb-1 mt-0">Gabriel J. Snyder</h5>
                                                <p> Business Analyst </p>
                                                <p class="mb-0 text-muted">
                                                    <span class="font-italic"><b>"</b>Disrupt pork belly poutine, asymmetrical tousled succulents selfies. You probably haven't heard of them tattooed master cleanse live-edge keffiyeh </span>
                                                </p>
                                            </div> <!-- end media-body -->
                                        </div> <!-- end media -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end col -->

                                <div class="card mb-0 mt-2 draggable-item" id="6">
                                    <div class="card-body p-3">
                                        <div class="media">
                                            <img src="<?php echo base_url('uploads/user_image/1.jpg'); ?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
                                            <div class="media-body">
                                                <h5 class="mb-1 mt-0">Louie C. Mason</h5>
                                                <p>Human Resources</p>
                                                <p class="mb-0 text-muted">
                                                    <span class="font-italic"><b>"</b>Disrupt pork belly poutine, asymmetrical tousled succulents selfies. You probably haven't heard of them tattooed master cleanse live-edge keffiyeh </span>
                                                </p>
                                            </div> <!-- end media-body -->
                                        </div> <!-- end media -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end col -->

                            </div> <!-- end company-list-2-->
                        </div> <!-- end div.bg-light-->
                    </div> <!-- end col -->

                </div> <!-- end row -->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<script type="text/javascript">
onDomChange(function(){
    var containerArray = ['company-list-left', 'company-list-right'];
    for(var i = 0; i < containerArray.length; i++) {
        $('#'+containerArray[i]).each(function () {
            $(this).find('.draggable-item').each(function() {
                console.log(this.id);
            });
        });
    }
    console.log('------------');
});
</script>
