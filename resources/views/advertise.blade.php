
@extends('layouts.app')

@section('content')
  
  <div class="slider-item fifth-page d-flex justify-content-center align-items-center position-relative"
                id="contact">
                <div class="section-history-title d-none d-lg-block"><span>advertiser</span></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-6">
                            <div class="contact-headings">
                                <h2 class="contact-headings-title mb-sm-3 text-light">Get in touch<br>with us!</h2>
                            </div>
                            <div class="contact-headings-para">
                                <p>We are always open<br>to new projects and<br>partnerships</p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <form class="row g-3 mt-sm-3 mt-0" method="post" action="{{route('advertiserSave')}}">
                                @csrf
                                <div class="row">
                                    <label for="name" class="form-label mb-0 text-light">Name
                                        <span class="required-label text-danger ">*</span>
                                    </label>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6 mt-0">

                                        <input type="" class="form-control rounded-0" name="first_name" id="first_name" required>
                                        <label for="name" class="text-light ">First</label>
                                        @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    </div>
                                    <div class="col-md-6 mt-0">

                                        <input type="text" class="form-control rounded-0"  name="last_name" id="last_name" required>
                                        <label for="lastname" class="text-light ">Last</label>
                                         @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="email" class="form-label text-light">Email <span
                                                class="required-label text-danger ">*</span></label>
                                        <input type="email" class="form-control rounded-0" id="email" name="email" placeholder="" required>
                                            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label text-light">Comment or Message </label>
                                    <textarea rows="4" class="w-100 farm" name="note">
                                    </textarea>                                    </textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn submitbutton">Submit</button>
                                </div>

                                @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
         @endsection
            
            
            <!-- fifth section ends  -->