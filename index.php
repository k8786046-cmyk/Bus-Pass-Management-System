<?php
require_once 'config.php';
$pageTitle = 'Home';

// ---- Handle contact form submission ----
$contact_success = '';
$contact_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        $contact_error = "Please fill in every field before sending.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $contact_success = "Thanks, $name — your message has been sent. Our support team will reply within 2 working days.";
        } else {
            $contact_error = "Something went wrong. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

require 'includes/header.php';
?>

<!-- ================= HERO ================= -->
<header class="hero">
  <div class="container">
    <div class="hero-copy">
      <div class="eyebrow-route">Route 24 · City Transit Authority</div>
      <h1>Your city, one card away.</h1>
      <p class="lead">Apply for a student, general, or senior citizen bus pass online, track its approval, and renew it before it expires — all without standing in a counter queue.</p>
      <div class="hero-actions">
        <a href="register.php" class="btn btn-amber">Apply for a Pass</a>
        <a href="login.php" class="btn btn-outline-light">Track My Application</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat"><b>48</b><span>City &amp; suburban routes</span></div>
        <div class="hero-stat"><b>72 hrs</b><span>Average approval time</span></div>
        <div class="hero-stat"><b>3</b><span>Pass categories</span></div>
      </div>
    </div>
    <div class="hero-graphic">
      <svg class="route-graphic" viewBox="0 0 360 320" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Illustration of a bus route line connecting five stops">
        <path d="M40 40 C 120 40, 60 140, 160 140 S 260 240, 320 280" stroke="#FCA311" stroke-width="4" fill="none" stroke-linecap="round"/>
        <circle cx="40" cy="40" r="9" fill="#F5F3EE"/>
        <circle cx="40" cy="40" r="4" fill="#14213D"/>
        <circle cx="160" cy="140" r="9" fill="#F5F3EE"/>
        <circle cx="160" cy="140" r="4" fill="#14213D"/>
        <circle cx="230" cy="205" r="9" fill="#F5F3EE"/>
        <circle cx="230" cy="205" r="4" fill="#14213D"/>
        <circle cx="320" cy="280" r="11" fill="#FCA311"/>
        <circle cx="320" cy="280" r="4.5" fill="#14213D"/>
        <text x="55" y="35" fill="#C9CEDB" font-family="Inter, sans-serif" font-size="12">Depot</text>
        <text x="175" y="132" fill="#C9CEDB" font-family="Inter, sans-serif" font-size="12">College Rd</text>
        <text x="245" y="198" fill="#C9CEDB" font-family="Inter, sans-serif" font-size="12">City Centre</text>
        <text x="255" y="300" fill="#FCA311" font-family="Inter, sans-serif" font-size="12" font-weight="600">You are here</text>
      </svg>
    </div>
  </div>
</header>

<!-- ================= ABOUT ================= -->
<section id="about">
  <div class="container about-grid">
    <div class="about-copy">
      <div class="section-tag">About the Scheme</div>
      <h2>Built to replace the paperwork counter</h2>
      <p>The CityLine Bus Pass portal digitises how commuters apply for and manage travel passes across the city's bus network. What used to take a trip to the depot office now takes about five minutes online.</p>
      <p>Every application is reviewed by the transit authority, and you can check its status at any time from your dashboard instead of calling the helpline.</p>
      <ul class="about-list">
        <li><span class="mark">01</span> Apply from any device — no printed forms required.</li>
        <li><span class="mark">02</span> Upload your ID proof once; reuse it for renewals.</li>
        <li><span class="mark">03</span> Get notified the moment your pass is approved.</li>
      </ul>
    </div>
    <div class="about-copy">
      <div class="section-tag">Why it matters</div>
      <h2>Fewer queues, faster commutes</h2>
      <p>Bus passes are the cheapest way to travel across the city, but the old process meant long waits at the depot. This system lets students, daily commuters, and senior citizens apply from home and pick up their pass once it's approved.</p>
      <p>Admin staff review applications from a single dashboard, which cuts processing time from days to hours during peak enrolment season.</p>
    </div>
  </div>
</section>

<!-- ================= SERVICES / PASS TYPES ================= -->
<section id="services" class="bg-alt">
  <div class="container">
    <div class="section-head">
      <div class="section-tag">Pass Types</div>
      <h2>Choose the pass that fits your commute</h2>
      <p>Every category covers the full city network. Pricing shown is per month and is set by the transit authority.</p>
    </div>
    <div class="pass-grid">
      <div class="pass-card student">
        <div class="swatch"></div>
        <h3>Student Pass</h3>
        <p>For school and college students travelling on a valid institution ID.</p>
        <div class="price">₹250 <span>/ month</span></div>
      </div>
      <div class="pass-card general">
        <div class="swatch"></div>
        <h3>General Pass</h3>
        <p>For daily commuters travelling to work across any city route.</p>
        <div class="price">₹600 <span>/ month</span></div>
      </div>
      <div class="pass-card senior">
        <div class="swatch"></div>
        <h3>Senior Citizen Pass</h3>
        <p>Discounted travel for residents aged 60 and above, city-wide.</p>
        <div class="price">₹150 <span>/ month</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ================= HOW IT WORKS ================= -->
<section id="how">
  <div class="container">
    <div class="section-head">
      <div class="section-tag">Process</div>
      <h2>Four steps from sign-up to travel</h2>
    </div>
    <div class="steps">
      <div class="step">
        <span class="num">1</span>
        <h4>Create an account</h4>
        <p>Register with your name, phone number, and email address.</p>
      </div>
      <div class="step">
        <span class="num">2</span>
        <h4>Submit your application</h4>
        <p>Pick a pass type, your route, and enter your ID proof number.</p>
      </div>
      <div class="step">
        <span class="num">3</span>
        <h4>Wait for review</h4>
        <p>The transit authority verifies your details, usually within 72 hours.</p>
      </div>
      <div class="step">
        <span class="num">4</span>
        <h4>Collect &amp; travel</h4>
        <p>Once approved, your dashboard shows your pass validity — you're set.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= CONTACT ================= -->
<section id="contact" class="bg-alt">
  <div class="container contact-grid">
    <div class="contact-info">
      <div class="section-tag">Contact</div>
      <h2>Need help with your pass?</h2>
      <p>Reach the CityLine support desk for anything the portal can't answer — lost passes, refunds, or route changes.</p>

      <div class="contact-detail">
        <span class="ic">☎</span>
        <div><b>Helpline</b><span>1800-266-2400 (Mon–Sat, 9am–6pm)</span></div>
      </div>
      <div class="contact-detail">
        <span class="ic">✉</span>
        <div><b>Email</b><span>support@citylinebus.example</span></div>
      </div>
      <div class="contact-detail">
        <span class="ic">⚑</span>
        <div><b>Depot Office</b><span>12 Anna Salai, City Transit Depot, Chennai</span></div>
      </div>
    </div>

    <div class="form-shell wide">
      <h2>Send us a message</h2>
      <p class="subtext">Fill this in and our support team will get back to you by email.</p>

      <?php if ($contact_success): ?><div class="alert alert-success"><?php echo htmlspecialchars($contact_success); ?></div><?php endif; ?>
      <?php if ($contact_error): ?><div class="alert alert-error"><?php echo htmlspecialchars($contact_error); ?></div><?php endif; ?>

      <form method="POST" action="index.php#contact">
        <div class="two-col">
          <div class="field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required>
          </div>
          <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required>
          </div>
        </div>
        <div class="field">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="What is this about?" required>
        </div>
        <div class="field">
          <label for="message">Message</label>
          <textarea id="message" name="message" placeholder="Tell us what happened..." required></textarea>
        </div>
        <button type="submit" name="send_message" class="btn btn-ink btn-block">Send Message</button>
      </form>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
