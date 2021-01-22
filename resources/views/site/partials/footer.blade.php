<!-- ========================= FOOTER ========================= -->
<footer class="section-footer bg-dark white">
    <div class="container">
        <section class="footer-top padding-top">
            <div class="row">
                <aside class="col-sm-3 col-md-3 white">
                    <h5 class="title">Customer Services</h5>
                    <ul class="list-unstyled">
                        <li> <a href="{{ route('custumer.help') }}">Help center</a></li>
                        <li> <a href="{{ route('custumer.money') }}">Money refund</a></li>
                        <li> <a href="{{ route('custumer.terms') }}">Terms and Policy</a></li>
                        <li> <a href="#">Open dispute</a></li>
                    </ul>
                </aside>
                <aside class="col-sm-3  col-md-3 white">
                    <h5 class="title">My Account</h5>
                    <ul class="list-unstyled">
                        <li> <a href="{{ route('login') }}"> User Login </a></li>
                        <li> <a href="{{ route('register') }}"> User register </a></li>
                        <li> <a href="#"> Account Setting </a></li>
                        <li> <a href="{{ route('account.orders') }}"> My Orders </a></li>
                        <li> <a href="#"> My Wishlist </a></li>
                    </ul>
                </aside>
                <aside class="col-sm-3  col-md-3 white">
                    <h5 class="title">About</h5>
                    <ul class="list-unstyled">
                        <li> <a href="{{ route('about.history') }}"> Our history </a></li>
                        <li> <a href="{{ route('about.buy') }}"> How to buy </a></li>
                        <li> <a href="{{ route('about.delivery') }}"> Delivery and payment </a></li>
                        <li> <a href="#"> Advertice </a></li>
                        <li> <a href="#"> Partnership </a></li>
                    </ul>
                </aside>
                <aside class="col-sm-3">
                    <article class="white">
                        <h5 class="title">Contacts</h5>
                        <p>
                            <strong>Phone: </strong> +123456789
                            <br>
                            <strong>Fax:</strong> +123456789
                        </p>

                        <div class="btn-group white">
                            <a class="btn btn-facebook" title="Facebook" target="_blank" href="{{ config('settings.social_facebook') }}"><i
                                    class="fab fa-facebook-f  fa-fw"></i></a>
                            <a class="btn btn-instagram" title="Instagram" target="_blank" href="{{ config('settings.social_instagram') }}"><i
                                    class="fab fa-instagram  fa-fw"></i></a>
                            <a class="btn btn-youtube" title="Youtube" target="_blank" href="{{ config('settings.social_youtube') }}"><i
                                    class="fab fa-youtube  fa-fw"></i></a>
                            <a class="btn btn-twitter" title="Twitter" target="_blank" href="{{ config('settings.social_twitter') }}"><i
                                    class="fab fa-twitter  fa-fw"></i></a>
                        </div>
                    </article>
                </aside>
            </div>
            <!-- row.// -->
            <br>
        </section>
        <section class="footer-bottom row border-top-white">
           
            <div class="col-sm-6">
                <p class="text-md-right text-white-50">
                {{ config('settings.footer_copyright_text') }}
                    <br>
                    
                </p>
                <script>
                            document.write(new Date().getFullYear());
                        </script> 
            </div>
        </section>
        <!-- //footer-top -->
    </div>
    <!-- //container -->
</footer>
<!-- ========================= FOOTER END // ========================= -->
