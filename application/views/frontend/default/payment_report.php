<?php
    $payment_histories = $this->crud_model->get_instructor_wise_payment_history($user_id);
?>
<div class="container">
    <div class="row">
        <div class="col">
            <table class = "table">
                <thead>
                    <tr>
                        <th><?php echo get_phrase('course_title'); ?></th>
                        <th><?php echo get_phrase('total_amount'); ?></th>
                        <th><?php echo get_phrase('instructor_revenue'); ?></th>
                        <th><?php echo get_phrase('status'); ?></th>
                        <th><?php echo get_phrase('date_added'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(sizeof($payment_histories) > 0):
                            foreach ($payment_histories as $payment_history):
                            $course_details = $this->crud_model->get_course_by_id($payment_history['course_id'])->row_array();?>
                            <tr>
                                <td><?php echo $course_details['title']; ?></td>
                                <td><?php echo currency($payment_history['amount']); ?></td>
                                <td><?php echo currency($payment_history['instructor_revenue']); ?></td>
                                <td>
                                    <?php if ($payment_history['instructor_payment_status'] == 1): ?>
                                        <span style="font-weight: bold; color: #4CAF50;"><?php echo get_phrase('paid'); ?></span>
                                    <?php elseif($payment_history['instructor_payment_status'] == 0): ?>
                                        <span style="font-weight: bold; color: #f44336;"><?php echo get_phrase('pending'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('D, d/M/Y', $payment_history['date_added']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5"><span><?php echo get_phrase('no_payment_history_found'); ?></span></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
