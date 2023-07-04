
<div class="card position-absolute top-50 start-50 translate-middle">
    <div class="container height-100 d-flex justify-content-center align-items-center">
        <div class="position-relative">
            <div class="card p-2 text-center">
            <?php $this->load->view('layouts/_alert'); ?>
                <?= form_open('otp', ['method' => 'POST']); ?>
                <h3>Please Input OTP from your Email for verification</h3>
                <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2"> 
                    <?= form_input('otp_1', $input->otp_1, ['class' => 'm-2 text-center form-control rounded', 'required' => true, 'id' => 'first', 'type'=> 'text' , 'maxlength' => 1]); ?>
                    <?= form_input('otp_2', $input->otp_2, ['class' => 'm-2 text-center form-control rounded', 'required' => true, 'id' => 'second', 'type'=> 'text' , 'maxlength' => 1]); ?>
                    <?= form_input('otp_3', $input->otp_3, ['class' => 'm-2 text-center form-control rounded', 'required' => true, 'id' => 'third', 'type'=> 'text' , 'maxlength' => 1]); ?>
                    <?= form_input('otp_4', $input->otp_4, ['class' => 'm-2 text-center form-control rounded', 'required' => true, 'id' => 'fourth', 'type'=> 'text' , 'maxlength' => 1]); ?>
                </div>
                <?= form_error('otp_1'); ?>
                <?= form_error('otp_2'); ?>
                <?= form_error('otp_3'); ?>
                <?= form_error('otp_4'); ?>
                <div class="mt-4"> 
                    <button class="btn btn-danger px-4 validate" type="submit">Validate</button> 
                </div>
                <?= form_close(); ?>
                <?php if($this->session->flashdata('resend_otp')) : ?>
                    <?= form_open("otp/resend", ['method' => 'POST']); ?>
                    <div class="mt-4"> 
                        <button class="btn btn-warning px-4 validate" type="submit">Resend</button> 
                    </div>
                    <?= form_close(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>