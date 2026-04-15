  </main>

  <!-- ── FOOTER ── -->
  <footer class="mh-footer">
    <div class="container">
      <div class="row g-5">
        <!-- Brand Column -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand">MobileHub</div>
          <p class="footer-desc">
            Your premium destination for the latest smartphones, accessories, and expert device services. We deliver quality and trust.
          </p>
          <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6">
          <h4 class="footer-title">Quick Links</h4>
          <ul class="footer-links">
            <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>/shop.php">Shop</a></li>
            <li><a href="<?php echo SITE_URL; ?>/services.php">Services</a></li>
            <li><a href="<?php echo SITE_URL; ?>/cart.php">Cart</a></li>
            <li><a href="<?php echo SITE_URL; ?>/profile.php">My Account</a></li>
          </ul>
        </div>

        <!-- Categories -->
        <div class="col-lg-2 col-md-6">
          <h4 class="footer-title">Categories</h4>
          <ul class="footer-links">
            <li><a href="<?php echo SITE_URL; ?>/shop.php?category=smartphones">Smartphones</a></li>
            <li><a href="<?php echo SITE_URL; ?>/shop.php?category=tablets">Tablets</a></li>
            <li><a href="<?php echo SITE_URL; ?>/shop.php?category=earbuds-audio">Earbuds & Audio</a></li>
            <li><a href="<?php echo SITE_URL; ?>/shop.php?category=smartwatches">Smartwatches</a></li>
            <li><a href="<?php echo SITE_URL; ?>/shop.php?category=accessories">Accessories</a></li>
          </ul>
        </div>

        <!-- Contact & Newsletter -->
        <div class="col-lg-4 col-md-6">
          <h4 class="footer-title">Stay Updated</h4>
          <p class="footer-desc" style="max-width: none;">Subscribe for exclusive deals, new launches, and tech news.</p>
          <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Subscribed successfully!', 'success'); this.reset();">
            <input type="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
          </form>
          <div class="mt-4">
            <p class="footer-desc mb-1" style="max-width: none;"><i class="bi bi-geo-alt me-2"></i>Tirunelveli, Tamil Nadu, India</p>
            <p class="footer-desc mb-1" style="max-width: none;"><i class="bi bi-telephone me-2"></i>+91 98765 43210</p>
            <p class="footer-desc" style="max-width: none;"><i class="bi bi-envelope me-2"></i>support@mobilehub.com</p>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="footer-bottom">
        <div class="footer-copy">© <?php echo date('Y'); ?> MobileHub — All rights reserved.</div>
        <a href="#" class="footer-back-top" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">
          Back to top <i class="bi bi-arrow-up"></i>
        </a>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
  <script src="<?php echo SITE_URL; ?>/assets/js/cart.js"></script>
</body>
</html>
