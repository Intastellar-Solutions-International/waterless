<?php
/* Template Name: Product Page */
get_header();

$compatible_housings = get_post_meta(get_the_ID(), '_compatible_housings', true);
$compatible_urinals  = get_post_meta(get_the_ID(), '_compatible_urinals', true);
?>
<main class="main-content product-page">
    <section class="content ppad grid cols-3 --left-small">
        <?php if (!empty($compatible_housings)) : ?>
            <div class="compatibility-section">
                <h3><?php _e('Compatible Housings', 'waterless'); ?></h3>
                <?php
                foreach ($compatible_housings as $housing_id) :
                    $housing = get_post($housing_id);
                    if ($housing && $housing->post_status === 'publish') :
                        echo '<a class="compatible-items" href="' . get_permalink($housing_id) . '">
                        <img src="' . get_the_post_thumbnail_url($housing_id, 'thumbnail') . '" alt="' . esc_attr($housing->post_title) . '">
                        </a>';
                    endif;
                endforeach;
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($compatible_urinals)) : ?>
            <div class="compatibility-section">
                <h3><?php _e('Compatible Urinals', 'waterless'); ?></h3>
                <?php
                foreach ($compatible_urinals as $urinal_id) :
                    $urinal = get_post($urinal_id);
                    if ($urinal && $urinal->post_status === 'publish') :
                        echo '<a class="compatible-items" href="' . get_permalink($urinal_id) . '">
                        <img src="' . get_the_post_thumbnail_url($urinal_id, 'thumbnail') . '" alt="' . esc_attr($urinal->post_title) . '"></a>';
                    endif;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
        <!-- Product image with magnifier -->
        <section class="img-magnifier-container">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['id' => 'product-image', 'class' => 'product-image']); ?>
            <?php endif; ?>
        </section>
        <section class="grid cols-2">
            <article class="--stretched">
                <h1>
                    <?php echo esc_html(get_the_title()) ?>
                </h1>
                <a class="cta" href="#">Hvordan vedligeholdes det?</a>
            </article>
            <?php
            if (
                get_post_meta(get_the_ID(), 'dimension_height', true) != ""
                && get_post_meta(get_the_ID(), 'dimension_width', true) != ""
                && get_post_meta(get_the_ID(), 'dimension_depth', true) != ""
            ):
            ?>
                <article>
                    <h3>Dimensions</h3>
                    <ul>
                        <li>Højde: <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension_height', true)); ?> mm</li>
                        <li>Bredde: <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension_width', true)); ?> mm</li>
                        <li>Dybdde: <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension_depth', true)); ?> mm</li>
                    </ul>
                </article>
            <?php
            endif;
            ?>
            <?php
            if (get_post_meta(get_the_ID(), 'material', true) != ""):
            ?>
                <article>
                    <h3>Material</h3>
                    <p><?php echo esc_html(get_post_meta(get_the_ID(), 'material', true)) ?></p>
                </article>
            <?php
            endif;
            ?>
            <article class="--stretched">
                <h3>Farve</h3>
                <p><?php echo esc_html(get_post_meta(get_the_ID(), 'color', true)); ?></p>
                <section>
                    <?php
                    if (get_post_meta(get_the_ID(), 'plumbing_no', true) != ""):
                    ?>
                        <p>VVS nr.: <?php echo esc_html(get_post_meta(get_the_ID(), 'plumbing_no', true)); ?></p>
                    <?php endif; ?>
                    <p>Waterless nr.: <?php echo esc_html(get_post_meta(get_the_ID(), 'waterless_no', true)); ?></p>
                </section>
                <section>
                    <p><?php echo apply_filters('the_content', get_post_field('post_content', get_the_ID())) ?></p>
                </section>
            </article>
            <?php if (get_post_meta(get_the_ID(), "product_links", true)):
            ?>
                <article class="--stretched">
                    <h3>Produktblade</h3>
                    <?php
                    $links = get_post_meta(get_the_ID(), 'product_links', true);

                    if (!empty($links)) {
                        foreach ($links as $link) {
                            printf(
                                '<a href="%s" target="_blank">%s</a><br>',
                                esc_url($link['url']),
                                esc_html($link['text'])
                            );
                        }
                    }

                    ?>
                </article>
            <?php
            endif;
            ?>
            <article class="--stretched">
                <h3>Teknisk data</h3>
                <?php if ($cad = get_post_meta(get_the_ID(), 'cad_file', true)) : ?>
                    <a class="tech-links" href="<?php echo esc_url($cad); ?>" download>CAD file download</a>
                <?php endif; ?>

                <?php if ($zip = get_post_meta(get_the_ID(), 'zip_file', true)) : ?>
                    <a class="tech-links" href="<?php echo esc_url($zip); ?>" download>Zip file download</a>
                <?php endif; ?>

                <?php if ($drawing = get_post_meta(get_the_ID(), 'drawing_file', true)) : ?>
                    <a class="tech-links" href="<?php echo esc_url($drawing); ?>" download>Teknisk tegning download</a>
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
<?php get_footer(); ?>