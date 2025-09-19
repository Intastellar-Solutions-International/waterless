<?php
/* Template Name: Product Page */
get_header();
?>
<main class="main-content product-page">
    <section class="content ppad grid cols-3 --left-small">
        <!-- Compatible housing -->
        <section>
            <h2>Compatible housing</h2>
            <img class="compatible-items" src="<?php echo get_template_directory_uri(); ?>/assets/products/housing-h1.png" alt="Housing H1">
            <img class="compatible-items" src="<?php echo get_template_directory_uri(); ?>/assets/products/housing-h3.png" alt="Housing H3">
        </section>
        <!-- Product image with magnifier -->
        <section class="img-magnifier-container">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['id' => 'product-image', 'class' => 'product-image']); ?>
            <?php endif; ?>
        </section>
        <section class="grid cols-2">
            <article class="--stretched">
                <h1>Waterless urinal Eco 100% recycled plastic</h1>
                <a class="cta" href="#">How to use?</a>
            </article>
            <article>
                <h3>Dimensions</h3>
                <ul>
                    <li>Height: <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension_height', true)); ?> mm</li>
                    <li>Width: <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension_width', true)); ?> mm</li>
                    <li>Depth: <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension_depth', true)); ?> mm</li>
                </ul>
            </article>
            <article>
                <h3>Material</h3>
                <p>100% recycled plastic</p>
            </article>
            <article class="--stretched">
                <h3>Color</h3>
                <p><?php echo esc_html(get_post_meta(get_the_ID(), 'product_color', true)); ?></p>
                <section>
                    <p>Plumbing no.: <?php echo esc_html(get_post_meta(get_the_ID(), 'plumbing_no', true)); ?></p>
                    <p>Waterless no.: <?php echo esc_html(get_post_meta(get_the_ID(), 'waterless_no', true)); ?></p>
                </section>
                <section>
                    <p>The drain pipe is freely accessible. Used for floor drains or wall drains or where several urinals need to be connected in series, e.g. to replace urinal troughs.</p>
                </section>
            </article>
            <article class="--stretched">
                <h3>Technical data</h3>
                <?php if ($cad = get_post_meta(get_the_ID(), 'cad_file', true)) : ?>
                    <a class="tech-links" href="<?php echo esc_url($cad); ?>" download>CAD file download</a>
                <?php endif; ?>

                <?php if ($zip = get_post_meta(get_the_ID(), 'zip_file', true)) : ?>
                    <a class="tech-links" href="<?php echo esc_url($zip); ?>" download>Zip file download</a>
                <?php endif; ?>

                <?php if ($drawing = get_post_meta(get_the_ID(), 'drawing_file', true)) : ?>
                    <a class="tech-links" href="<?php echo esc_url($drawing); ?>" download>Technical drawing download</a>
                <?php endif; ?>
            </article>
        </section>
    </section>
    <img class="product-image-full" src="<?php echo get_template_directory_uri(); ?>/assets/c37749ac7c92128d32ce986adce32edb663d0e68.jpg" alt="<?php the_title(); ?>">
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