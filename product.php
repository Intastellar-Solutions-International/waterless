<?php
$pageTitle = "Waterless urinal Eco 100% recycled plastic";
include_once 'components/head.php';
?>
<main class="main-content product-page">
    <section class="content ppad grid cols-3 --left-small">
        <section>
            <h2>Compatible housing</h2>
            <img class="compatible-items" src="assets/products/housing-h1.png" alt="Housing H1">
            <img class="compatible-items" src="assets/products/housing-h3.png" alt="Housing H3">
        </section>
        <section class="img-magnifier-container">
            <img id="product-image" class="product-image" src="assets/products/urinal-eco-12.png" alt="Waterless urinal Eco 100% recycled plastic">
        </section>
        <section class="grid cols-2">
            <article class="--stretched">
                <h1>Waterless urinal Eco 100% recycled plastic</h1>
                <a class="cta" href="#">How to use?</a>
            </article>
            <article>
                <h3>Dimensions</h3>
                <ul>
                    <li>Height: 620 mm</li>
                    <li>Width: 360 mm</li>
                    <li>Depth: 370 mm</li>
                </ul>
            </article>
            <article>
                <h3>Material</h3>
                <p>100% recycled plastic</p>
            </article>
            <article class="--stretched">
                <h3>Color</h3>
                <p>Dark Grey</p>
                <section>
                    <p>Plumbing no.: 61.8164.102</p>
                    <p>Waterless no.: No 12</p>
                </section>
                <section>
                    <p>The drain pipe is freely accessible. Used for floor drains or wall drains or where several urinals need to be connected in series, e.g. to replace urinal troughs.</p>
                </section>
            </article>
            <article class="--stretched">
                <h3>Technical data</h3>
                <a class="tech-links">CAD file download</a>
                <a class="tech-links">Zip file download</a>
                <a class="tech-links">Technical drawing download</a>
            </article>
        </section>
    </section>
    <img class="product-image-full" src="assets/c37749ac7c92128d32ce986adce32edb663d0e68.jpg" alt="Waterless urinal Eco 100% recycled plastic">
</main>
<script>
    magnify("product-image", 3);

    function magnify(imgID, zoom) {
        var img, glass, w, h, bw;
        img = document.getElementById(imgID);

        /* Create magnifier glass: */
        glass = document.createElement("DIV");
        glass.setAttribute("class", "img-magnifier-glass");

        /* Insert magnifier glass when mouse enters image container: */
        img.parentElement.insertBefore(glass, img);

        /* Set background properties for the magnifier glass: */
        glass.style.backgroundImage = "url('" + img.src + "')";
        glass.style.backgroundRepeat = "no-repeat";
        glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
        bw = 3;
        w = glass.offsetWidth / 2;
        h = glass.offsetHeight / 2;

        /* Execute a function when someone moves the magnifier glass over the image: */
        glass.addEventListener("mousemove", moveMagnifier);
        img.addEventListener("mousemove", moveMagnifier);

        /*and also for touch screens:*/
        glass.addEventListener("touchmove", moveMagnifier);
        img.addEventListener("touchmove", moveMagnifier);

        function moveMagnifier(e) {
            var pos, x, y;
            /* Prevent any other actions that may occur when moving over the image */
            e.preventDefault();
            /* Get the cursor's x and y positions: */
            pos = getCursorPos(e);
            x = pos.x;
            y = pos.y;
            /* Prevent the magnifier glass from being positioned outside the image: */
            if (x > img.width - (w / zoom)) {
                x = img.width - (w / zoom);
            }
            if (x < w / zoom) {
                x = w / zoom;
            }
            if (y > img.height - (h / zoom)) {
                y = img.height - (h / zoom);
            }
            if (y < h / zoom) {
                y = h / zoom;
            }
            /* Set the position of the magnifier glass: */
            glass.style.left = (x - w) + "px";
            glass.style.top = (y - h) + "px";
            /* Display what the magnifier glass "sees": */
            glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
        }

        function getCursorPos(e) {
            var a, x = 0,
                y = 0;
            e = e || window.event;
            /* Get the x and y positions of the image: */
            a = img.getBoundingClientRect();
            /* Calculate the cursor's x and y coordinates, relative to the image: */
            x = e.pageX - a.left;
            y = e.pageY - a.top;
            /* Consider any page scrolling: */
            x = x - window.pageXOffset;
            y = y - window.pageYOffset;
            return {
                x: x,
                y: y
            };
        }
    }
</script>
<?php
include_once 'components/footer.php';
?>