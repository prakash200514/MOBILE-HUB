<?php
/**
 * MobileHub — Services & Booking Page
 */
$pageTitle = 'Services';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$success = '';
$error = '';

// Handle service booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = sanitize($_POST['customer_name'] ?? '');
    $customerEmail = sanitize($_POST['customer_email'] ?? '');
    $customerPhone = sanitize($_POST['customer_phone'] ?? '');
    $deviceName = sanitize($_POST['device_name'] ?? '');
    $serviceType = sanitize($_POST['service_type'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $bookingDate = sanitize($_POST['booking_date'] ?? '');
    $userId = isLoggedIn() ? $_SESSION['user_id'] : null;

    if ($customerName && $customerPhone && $deviceName && $serviceType) {
        $stmt = $conn->prepare("INSERT INTO service_bookings (user_id, customer_name, customer_email, customer_phone, device_name, service_type, description, booking_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $userId, $customerName, $customerEmail, $customerPhone, $deviceName, $serviceType, $description, $bookingDate);
        
        if ($stmt->execute()) {
            $success = 'Service booked successfully! We will contact you shortly.';
        } else {
            $error = 'Failed to book service. Please try again.';
        }
        $stmt->close();
    } else {
        $error = 'Please fill in all required fields.';
    }
}

require_once __DIR__ . '/includes/header.php';
$user = isLoggedIn() ? getCurrentUser() : null;
?>

  <!-- Services Hero -->
  <section class="hero-section" style="min-height: 50vh; padding: 120px 0 60px;">
    <div class="container text-center">
      <div class="hero-badge mx-auto reveal">🔧 Expert Technicians</div>
      <h1 class="hero-title reveal" style="max-width: 700px; margin: 0 auto;">
        Professional <span class="gradient-text">Device Services</span>
      </h1>
      <p class="hero-desc reveal mx-auto" style="max-width: 600px;">
        From screen repairs to software updates — our certified technicians handle it all with precision and care.
      </p>
    </div>
  </section>

  <!-- Services Grid -->
  <section class="section-padding" style="padding-top: 40px;">
    <div class="container">
      <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-1">
            <div class="service-card-icon" style="background: #fef2f2; color: #dc2626;"><i class="bi bi-phone"></i></div>
            <h3 class="service-card-title">Screen Repair</h3>
            <p class="service-card-desc">OEM-quality display replacement with warranty. Supports all major brands.</p>
            <div class="service-card-price">Starting from ₹1,499</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-2">
            <div class="service-card-icon" style="background: #ecfdf5; color: #059669;"><i class="bi bi-battery-charging"></i></div>
            <h3 class="service-card-title">Battery Replacement</h3>
            <p class="service-card-desc">Genuine battery replacement to restore your phone's full-day battery life.</p>
            <div class="service-card-price">Starting from ₹999</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-3">
            <div class="service-card-icon" style="background: #eff6ff; color: #2563eb;"><i class="bi bi-cpu"></i></div>
            <h3 class="service-card-title">Software Update</h3>
            <p class="service-card-desc">OS upgrades, malware removal, and performance optimization.</p>
            <div class="service-card-price">Starting from ₹499</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-1">
            <div class="service-card-icon" style="background: #f5f3ff; color: #7c3aed;"><i class="bi bi-droplet"></i></div>
            <h3 class="service-card-title">Water Damage Repair</h3>
            <p class="service-card-desc">Ultrasonic cleaning and component-level repair for water-damaged devices.</p>
            <div class="service-card-price">Starting from ₹1,999</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-2">
            <div class="service-card-icon" style="background: #fffbeb; color: #d97706;"><i class="bi bi-camera"></i></div>
            <h3 class="service-card-title">Camera Repair</h3>
            <p class="service-card-desc">Fix blurry images, broken lens, or camera module with precision tools.</p>
            <div class="service-card-price">Starting from ₹1,299</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-3">
            <div class="service-card-icon" style="background: #fdf4ff; color: #c026d3;"><i class="bi bi-shield-check"></i></div>
            <h3 class="service-card-title">General Checkup</h3>
            <p class="service-card-desc">Complete health check: diagnostics, cleaning, and system optimization.</p>
            <div class="service-card-price">Starting from ₹299</div>
          </div>
        </div>
      </div>

      <!-- Booking Form -->
      <div class="row justify-content-center" id="booking">
        <div class="col-lg-8">
          <div class="text-center mb-4">
            <div class="section-eyebrow justify-content-center reveal">Book Now</div>
            <h2 class="section-title reveal">Schedule Your Service</h2>
            <p class="section-subtitle mx-auto reveal">Fill in the details below and we'll get back to you within 24 hours</p>
          </div>

          <?php if ($success): ?>
            <div class="alert alert-success mb-4 reveal"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger mb-4 reveal"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
          <?php endif; ?>

          <div class="form-glass reveal">
            <form method="POST">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Your Name *</label>
                  <input type="text" class="form-control" name="customer_name" 
                         value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" placeholder="Full name" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone Number *</label>
                  <input type="tel" class="form-control" name="customer_phone" 
                         value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+91 XXXXX XXXXX" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email Address</label>
                  <input type="email" class="form-control" name="customer_email" 
                         value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" placeholder="you@example.com">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Device Name *</label>
                  <input type="text" class="form-control" name="device_name" placeholder="e.g., iPhone 16 Pro, Samsung S25" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Service Type *</label>
                  <select class="form-select" name="service_type" required>
                    <option value="">Select a service</option>
                    <option value="Screen Repair">Screen Repair</option>
                    <option value="Battery Replacement">Battery Replacement</option>
                    <option value="Software Update">Software Update</option>
                    <option value="Water Damage Repair">Water Damage Repair</option>
                    <option value="Camera Repair">Camera Repair</option>
                    <option value="General Checkup">General Checkup</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Preferred Date</label>
                  <input type="date" class="form-control" name="booking_date" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-12">
                  <label class="form-label">Issue Description</label>
                  <textarea class="form-control" name="description" rows="4" placeholder="Describe the issue with your device..."></textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn-gradient w-100 justify-content-center mt-2">
                    <i class="bi bi-calendar-check"></i> Book Service
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
