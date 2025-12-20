@extends('admin.layout.master')
@section('title', 'Settings')
@section('body')
<div class="app-content main-content">
    <div class="side-app">
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="page-title">Settings</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">System Configuration</h3>
                    </div>
                    <div class="card-body">
                        <div class="panel panel-primary">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu ">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab1" class="active" data-toggle="tab">General</a></li>
                                        <li><a href="#tab2" data-toggle="tab">Branding</a></li>
                                        <li><a href="#tab3" data-toggle="tab">Currency</a></li>
                                        <li><a href="#tab4" data-toggle="tab">Tax & Duty</a></li>
                                        <li><a href="#tab5" data-toggle="tab">Payment</a></li>
                                        <li><a href="#tab6" data-toggle="tab">Shipping</a></li>
                                        <li><a href="#tab7" data-toggle="tab">Notification</a></li>
                                        <li><a href="#tab8" data-toggle="tab">SEO</a></li>
                                        <li><a href="#tab9" data-toggle="tab">Social</a></li>
                                        <li><a href="#tab10" data-toggle="tab">Maintenance</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    
                                    <!-- TAB 1: GENERAL -->
                                    <div class="tab-pane active" id="tab1">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="general">
                                            <div class="form-group">
                                                <label class="form-label">Store Name</label>
                                                <input type="text" class="form-control" name="store_name" value="{{ $settings['store_name'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control" name="store_address" rows="3">{{ $settings['store_address'] ?? '' }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Contact Phone</label>
                                                <input type="text" class="form-control" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Support Email</label>
                                                <input type="email" class="form-control" name="support_email" value="{{ $settings['support_email'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Timezone</label>
                                                <input type="text" class="form-control" name="timezone" value="{{ $settings['timezone'] ?? 'UTC' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Date Format</label>
                                                <input type="text" class="form-control" name="date_format" value="{{ $settings['date_format'] ?? 'Y-m-d' }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save General Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 2: BRANDING -->
                                    <div class="tab-pane" id="tab2">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="group" value="branding">
                                            <div class="form-group">
                                                <label class="form-label">Logo</label>
                                                <input type="file" class="form-control" name="logo">
                                                @if(isset($settings['logo']))
                                                    <div class="mt-2">
                                                        <img src="{{ asset($settings['logo']) }}" alt="Logo" style="max-height: 50px;">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Favicon</label>
                                                <input type="file" class="form-control" name="favicon">
                                                @if(isset($settings['favicon']))
                                                    <div class="mt-2">
                                                        <img src="{{ asset($settings['favicon']) }}" alt="Favicon" style="max-height: 32px;">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Theme</label>
                                                <select class="form-control" name="theme_mode">
                                                    <option value="light" {{ ($settings['theme_mode'] ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                                                    <option value="dark" {{ ($settings['theme_mode'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Primary Color</label>
                                                <input type="color" class="form-control" name="primary_color" value="{{ $settings['primary_color'] ?? '#000000' }}" style="height: 40px;">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Branding Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 3: CURRENCY -->
                                    <div class="tab-pane" id="tab3">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="currency">
                                            <div class="form-group">
                                                <label class="form-label">Default Currency</label>
                                                <input type="text" class="form-control" name="currency_code" value="{{ $settings['currency_code'] ?? 'USD' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Currency Symbol Position</label>
                                                <select class="form-control" name="currency_symbol_position">
                                                    <option value="left" {{ ($settings['currency_symbol_position'] ?? '') == 'left' ? 'selected' : '' }}>Left</option>
                                                    <option value="right" {{ ($settings['currency_symbol_position'] ?? '') == 'right' ? 'selected' : '' }}>Right</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Decimal Precision</label>
                                                <input type="number" class="form-control" name="decimal_precision" value="{{ $settings['decimal_precision'] ?? '2' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Default Language</label>
                                                <input type="text" class="form-control" name="default_language" value="{{ $settings['default_language'] ?? 'en' }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Currency Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 4: TAX & DUTY -->
                                    <div class="tab-pane" id="tab4">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="tax">
                                            <div class="form-group">
                                                <label class="form-label">Default GST/VAT %</label>
                                                <input type="number" step="0.01" class="form-control" name="default_tax_rate" value="{{ $settings['default_tax_rate'] ?? '0' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Customs Fee %</label>
                                                <input type="number" step="0.01" class="form-control" name="customs_fee_rate" value="{{ $settings['customs_fee_rate'] ?? '0' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Insurance %</label>
                                                <input type="number" step="0.01" class="form-control" name="insurance_rate" value="{{ $settings['insurance_rate'] ?? '0' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Freight Charges</label>
                                                <input type="number" step="0.01" class="form-control" name="freight_charges" value="{{ $settings['freight_charges'] ?? '0' }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Tax Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 5: PAYMENT -->
                                    <div class="tab-pane" id="tab5">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="payment">
                                            
                                            <div class="form-group">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="hidden" name="payment_test_mode" value="0">
                                                    <input type="checkbox" class="custom-control-input" name="payment_test_mode" value="1" {{ ($settings['payment_test_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                                                    <span class="custom-control-label">Enable Test Mode (Sandbox)</span>
                                                </label>
                                            </div>

                                            <h5 class="mt-4">Stripe</h5>
                                            <div class="form-group">
                                                <label class="form-label">Stripe Key</label>
                                                <input type="text" class="form-control" name="stripe_key" value="{{ $settings['stripe_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Stripe Secret</label>
                                                <input type="text" class="form-control" name="stripe_secret" value="{{ $settings['stripe_secret'] ?? '' }}">
                                            </div>

                                            <h5 class="mt-4">Razorpay</h5>
                                            <div class="form-group">
                                                <label class="form-label">Razorpay Key</label>
                                                <input type="text" class="form-control" name="razorpay_key" value="{{ $settings['razorpay_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Razorpay Secret</label>
                                                <input type="text" class="form-control" name="razorpay_secret" value="{{ $settings['razorpay_secret'] ?? '' }}">
                                            </div>

                                            <h5 class="mt-4">PayPal</h5>
                                            <div class="form-group">
                                                <label class="form-label">PayPal Client ID</label>
                                                <input type="text" class="form-control" name="paypal_client_id" value="{{ $settings['paypal_client_id'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">PayPal Secret</label>
                                                <input type="text" class="form-control" name="paypal_secret" value="{{ $settings['paypal_secret'] ?? '' }}">
                                            </div>

                                            <button type="submit" class="btn btn-primary mt-3">Save Payment Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 6: SHIPPING -->
                                    <div class="tab-pane" id="tab6">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="shipping">
                                            <div class="form-group">
                                                <label class="form-label">Default Shipping Method</label>
                                                <input type="text" class="form-control" name="default_shipping_method" value="{{ $settings['default_shipping_method'] ?? 'Standard' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Calculation Type</label>
                                                <select class="form-control" name="shipping_calculation">
                                                    <option value="price" {{ ($settings['shipping_calculation'] ?? '') == 'price' ? 'selected' : '' }}>Price Based</option>
                                                    <option value="weight" {{ ($settings['shipping_calculation'] ?? '') == 'weight' ? 'selected' : '' }}>Weight Based</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Shipping Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 7: NOTIFICATION -->
                                    <div class="tab-pane" id="tab7">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="notification">
                                            <h5 class="mt-2">SMTP Settings</h5>
                                            <div class="form-group">
                                                <label class="form-label">SMTP Host</label>
                                                <input type="text" class="form-control" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">SMTP Port</label>
                                                <input type="text" class="form-control" name="smtp_port" value="{{ $settings['smtp_port'] ?? '587' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">SMTP Username</label>
                                                <input type="text" class="form-control" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">SMTP Password</label>
                                                <input type="password" class="form-control" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Encryption</label>
                                                <select class="form-control" name="smtp_encryption">
                                                    <option value="tls" {{ ($settings['smtp_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                                    <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Notification Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 8: SEO -->
                                    <div class="tab-pane" id="tab8">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="seo">
                                            <div class="form-group">
                                                <label class="form-label">Meta Title</label>
                                                <input type="text" class="form-control" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Meta Description</label>
                                                <textarea class="form-control" name="meta_description" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Meta Keywords</label>
                                                <textarea class="form-control" name="meta_keywords" rows="3">{{ $settings['meta_keywords'] ?? '' }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Robots.txt</label>
                                                <textarea class="form-control" name="robots_txt" rows="3">{{ $settings['robots_txt'] ?? '' }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save SEO Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 9: SOCIAL -->
                                    <div class="tab-pane" id="tab9">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="social">
                                            <div class="form-group">
                                                <label class="form-label">Facebook</label>
                                                <input type="text" class="form-control" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Instagram</label>
                                                <input type="text" class="form-control" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">WhatsApp</label>
                                                <input type="text" class="form-control" name="social_whatsapp" value="{{ $settings['social_whatsapp'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">YouTube</label>
                                                <input type="text" class="form-control" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">TikTok</label>
                                                <input type="text" class="form-control" name="social_tiktok" value="{{ $settings['social_tiktok'] ?? '' }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Social Settings</button>
                                        </form>
                                    </div>

                                    <!-- TAB 10: MAINTENANCE -->
                                    <div class="tab-pane" id="tab10">
                                        <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="group" value="maintenance">
                                            <div class="form-group">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="hidden" name="maintenance_mode" value="0">
                                                    <input type="checkbox" class="custom-control-input" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                                                    <span class="custom-control-label">Enable Maintenance Mode</span>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Maintenance Message</label>
                                                <textarea class="form-control" name="maintenance_message" rows="3">{{ $settings['maintenance_message'] ?? 'We are currently performing maintenance. Please check back later.' }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Save Maintenance Settings</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection