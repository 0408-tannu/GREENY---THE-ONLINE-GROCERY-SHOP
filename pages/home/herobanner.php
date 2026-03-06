<!-- <?php
// Array of image file names
$images = [
    "img1.jpg",
    "img2.jpg",
    "img3.jpg"
];
?> -->


<div class="slider">
    <!-- Radio Inputs -->
    <input type="radio" name="slide" id="slide1" checked />
    <input type="radio" name="slide" id="slide2" />
    <input type="radio" name="slide" id="slide3" />

    <!-- Slides -->
    <div class="slides">
        <div class="slide">
            <div class="herobanner-section">
                <div class="herobanner container">
                    <div class="hero-text">
                        <h4>Stay at home &</h4>
                        <h4>We bring the store</h4>
                        <h5> to your door</h5>
                        <p>Free shipping on all your order. we deliver, you enjoy</p>
                        <!-- <span>Save up to 50% on your first order</span> -->
                        <!-- <button>ORDER NOW</button> -->

            <a href="/pages/products/products.php">
                            
                                <button>ORDER NOW</button>
                            </a>
                    </div>
                    <div>
                        <img src="assets/images/herob.png" alt="Fresh Vegetables" style="width:700px ; height:650px; " />
                    </div>
                </div>
            </div>
        </div>

        <div class="slide">
            <div class="herobanner-section">
                <div class="herobanner container">
                    <div class="hero-text">
                        <h4>Fresh & healthy</h4>
                        <h5>organic food</h5>
                        <span>Save up to 20% off</span>
                        <!-- <button>ORDER NOW</button> -->
            <a href="/pages/products/products.php?category_id=1" >
                        
                                <button>ORDER NOW</button>
                            </a>
                    </div>
                    <div>
                        <img src="assets/images/basket.png" alt="Fresh Grocery" style="margin-top:30px; width:700px" />
                    </div>
                </div>
            </div>
        </div>

        <div class="slide">
            <div class="herobanner-section">
                <div class="herobanner container">
                    <div class="hero-text">
                        <h4>Fresh Fruits &</h4>
                        <h5>Big Discount</h5>
                        <span>Save up to 50% on your first order</span>
                        <!-- <button>ORDER NOW</button> -->

            <a href="/pages/products/products.php?category_id=5">
                       
                                <button>ORDER NOW</button>
                            </a>
                    </div>
                    <div>
                        <img src="assets/images/bascket3.png" alt="Fresh Fruits"  style="margin-top: 90px; object-fit: cover; width:800px"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Dots -->
    <div class="navigation">
        <label for="slide1"></label>
        <label for="slide2"></label>
        <label for="slide3"></label>
    </div>
</div>

<script>
// Auto-slide functionality enabled
let currentSlide = 1;
const totalSlides = 3;

function nextSlide() {
    currentSlide = currentSlide >= totalSlides ? 1 : currentSlide + 1;
    document.getElementById('slide' + currentSlide).checked = true;
}

// Auto-slide every 5 seconds
setInterval(nextSlide, 4000);
</script>
