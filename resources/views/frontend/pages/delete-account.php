@extends('frontend.layouts.master')

@section('content')

 <section class="delete-account-hero">
        <div class="container">
            <h1 class="wow fadeInUp" data-wow-delay="0.1s">Its Sad<br><span>To See You</span></h1>
            <p class="subtitle wow fadeInUp" data-wow-delay="0.2s">We're Sorry To See You Go!</p>
            <p class="description wow fadeInUp" data-wow-delay="0.3s">If You're Certain About Deleting Your Account, Please Read The Following Important Information</p>
            <div class="divider wow fadeInUp" data-wow-delay="0.4s"></div>
        </div>
    </section>

    <section class="delete-account-info">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-delay="0.5s">What Will Happen Next?</h2>
            
            <p class="wow fadeInUp" data-wow-delay="0.6s">
                <strong>Permanent Deletion:</strong> All your personal data will be permanently deleted.
            </p>
            <p class="wow fadeInUp" data-wow-delay="0.7s">
                <strong>Irreversible Action:</strong> This action cannot be undone.
            </p>
            <p class="wow fadeInUp" data-wow-delay="0.8s">
                <strong>Subscription Cancellation:</strong> Any outstanding subscriptions will be canceled.
            </p>
            
            <p class="wow fadeInUp" data-wow-delay="0.9s" style="margin-top: 25px;">
                If you still wish to proceed, please fill the form below:
            </p>
        </div>
    </section>

    <section id="deletionForm" class="deletion-request-section">
        <div class="container">
            <div class="deletion-form-card wow fadeInUp" data-wow-delay="0.2s">
                <div class="deletion-img-col"></div>
                
                <div class="deletion-form-col">
                    <h3>Request Account Deletion</h3>
                    
                    <form action="#" method="POST">
                        <input type="text" class="border-input" placeholder="Full Name *" required>
                        <input type="email" class="border-input" placeholder="Your Email *" required>
                        <textarea class="border-input" rows="3" placeholder="Reason for deleting account (Optional)"></textarea>
                        
                        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" style="margin-bottom: 25px;"></div>
                        
                        <button type="submit" class="submit-btn-red">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection