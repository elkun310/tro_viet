<footer class="bg-warning-subtle py-4 mt-5">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-4 g-3">
            <div>
                <h6>PHÒNG TRỌ, NHÀ TRỌ</h6>
                <ul class="list-unstyled">
                    <li><a href="#">Phòng trọ HCM</a></li>
                    <li><a href="#">Phòng trọ Hà Nội</a></li>
                </ul>
            </div>
            <div>
                <h6>THUÊ NHÀ NGUYÊN CĂN</h6>
                <ul class="list-unstyled">
                    <li><a href="#">Thuê nhà HCM</a></li>
                    <li><a href="#">Thuê nhà Hà Nội</a></li>
                </ul>
            </div>
            <div>
                <h6>PHƯƠNG THỨC THANH TOÁN</h6>
                <div class="d-flex gap-2">
                    <img src="{{ asset('images/payment/visa.png') }}" height="30" alt="Visa">
                    <img src="{{ asset('images/payment/zalo.png') }}" height="30" alt="ZaloPay">
                    <img src="{{ asset('images/payment/momo.png') }}" height="30" alt="MoMo">
                </div>
            </div>
            <div>
                <h6>LIÊN HỆ</h6>
                <p>Email: support@troviet.vn</p>
                <p>Hotline: 0909316890</p>
            </div>
        </div>

        <div class="text-center mt-4 small">
            &copy; {{ now()->year }} Công ty TNHH LBKCorp. All rights reserved.
        </div>
    </div>
</footer>
