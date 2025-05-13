@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Contact Us</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                  
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" value="{{ old('subject') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message" required>{{ old('message') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 shadow">
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-inline-block mb-2">
                                <i class="bi bi-geo-alt fs-3"></i>
                            </div>
                            <h5 class="fw-bold">Address</h5>
                            <p class="text-muted">Amman,Al zhour</p>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-inline-block mb-2">
                                <i class="bi bi-telephone fs-3"></i>
                            </div>
                            <h5 class="fw-bold">Phone</h5>
                            <p class="text-muted">0776684150</p>
                        </div>

                        <div class="col-md-4">
                            <div class="d-inline-block mb-2">
                                <i class="bi bi-envelope fs-3"></i>
                            </div>
                            <h5 class="fw-bold">Email</h5>
                            <p class="text-muted">malek.whdn@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
