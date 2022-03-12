<?php
$section_details = $this->crud_model->get_section('section', $param2)->row_array();
$lessons = $this->crud_model->get_lessons('section', $section_details['id'])->result_array();
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" id = "parent-div" data-plugin="dragula" data-containers='["lesson-list"]'>
                    <div class="col-md-12">
                        <div class="bg-dragula p-2 p-lg-4">
                            <h5 class="mt-0"><?php echo get_phrase('sort_lessons_of').': '.$section_details['title'].' '.get_phrase('section'); ?>
                                <button type="button" class="btn btn-outline-primary btn-sm btn-rounded alignToTitle" id = "lesson-sort-btn" onclick="sort()" name="button"><?php echo get_phrase('update_sorting'); ?></button>
                            </h5>
                            <div id="lesson-list" class="py-2">
                                <?php foreach ($lessons as $lesson): ?>
                                    <!-- item -->
                                    <div class="card mb-0 mt-2 draggable-item" id = "<?php echo $lesson['id']; ?>">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <h5 class="mb-1 mt-0"><?php echo $lesson['title']; ?></h5>
                                                </div> <!-- end media-body -->
                                            </div> <!-- end media -->
                                        </div> <!-- end card-body -->
                                    </div> <!-- end col -->
                                    <!-- item ends -->
                                <?php endforeach; ?>
                            </div> <!-- end company-list-1-->
                        </div> <!-- end div.bg-light-->
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>

<!-- Init Dragula -->
<script type="text/javascript">
! function(r) {
    "use strict";
    var a = function() {
        this.$body = r("body")
    };
    a.prototype.init = function() {
        r('[data-plugin="dragula"]').each(function() {
            var a = r(this).data("containers"),
            t = [];
            if (a)
            for (var n = 0; n < a.length; n++) t.push(r("#" + a[n])[0]);
            else t = [r(this)[0]];
            var i = r(this).data("handleclass");
            i ? dragula(t, {
                moves: function(a, t, n) {
                    return n.classList.contains(i)
                }
            }) : dragula(t)
        })
    }, r.Dragula = new a, r.Dragula.Constructor = a
}(window.jQuery),
function(a) {
    "use strict";
    window.jQuery.Dragula.init()
}();
</script>
<script type="text/javascript">
function sort() {
    var containerArray = ['lesson-list'];
    var itemArray = [];
    var itemJSON;
    for(var i = 0; i < containerArray.length; i++) {
        $('#'+containerArray[i]).each(function () {
            $(this).find('.draggable-item').each(function() {
                //console.log(this.id);
                itemArray.push(this.id);
            });
        });
    }

    itemJSON = JSON.stringify(itemArray);
    $.ajax({
        url: '<?php echo site_url('user/ajax_sort_lesson/');?>',
        type : 'POST',
        data : {itemJSON : itemJSON},
        success: function(response)
        {
            success_notify('<?php echo get_phrase('lessons_have_been_sorted'); ?>');
            setTimeout(
                function()
                {
                    location.reload();
                }, 1000);
            }
        });
    }
</script>
