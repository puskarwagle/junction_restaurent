<!-- Contact Section -->
<section id="contact" class="contact section">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="visit-two-area visit-page-area pt-100 pb-100">
        <div class="container">
            <div class="visit-two-form-content visit-page-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="visit-two-images">
                            <div class="visit-video">
                                <a href="https://www.youtube.com/watch?v=-z-wtyXjFIg" class="popup-youtube">
                                    <img src="assets/images/video-play.svg" alt="images">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="single-visit-two-form booking-table-form">
                            <div class="section-title left-title">
                                <span class="top-title">Visit Us Today</span>
                                <h2>Make A Reserve</h2>
                            </div>
                            <form wire:submit.prevent="submitReservation">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Name" wire:model="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="number" class="form-control" placeholder="Phone" wire:model="phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="number" id="wagledatefix" class="form-control" placeholder="No of people" wire:model="persons">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="input-group date" id="datetimepicke">
                                                <input type="date" class="form-control" wire:model="date">
                                                <span class="input-group-addon"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <select class="form-select" aria-label="Default select example" wire:model="time">
                                                    <option value="" disabled selected>Time</option>
                                                    <option value="08:00 AM – 11:00 AM">08:00 AM – 11:00 AM</option>
                                                    <option value="11:00 AM – 1:00 PM">11:00 AM – 1:00 PM</option>
                                                    <option value="1:00 PM – 05:00 PM">1:00 PM – 05:00 PM</option>
                                                    <option value="05:00 PM – 08:00 PM">05:00 PM – 08:00 PM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6 col-md-6">
                                            <button type="submit" class="default-btn">Book A Table</button>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
