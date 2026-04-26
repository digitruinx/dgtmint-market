<footer class="footer">
  <div class="container">
    <div class="footer__grid">
      <div>
        <div class="footer__brand">DGT<span class="dot">.</span>Market</div>
        <p class="footer__desc">India's digital product marketplace by Digitruinx Pvt Ltd. AI agents, courses, graphics, themes and more — built by creators, for creators.</p>
      </div>
      <div>
        <div class="footer__title">AI Products</div>
        <ul class="footer__links">
          <li><a href="<?= SITE_URL ?>/?cat=ai">AI Agents</a></li>
          <li><a href="<?= SITE_URL ?>/?cat=workflow">AI Workflows</a></li>
          <li><a href="<?= SITE_URL ?>/?cat=training">AI Training</a></li>
        </ul>
      </div>
      <div>
        <div class="footer__title">Digital Media</div>
        <ul class="footer__links">
          <li><a href="<?= SITE_URL ?>/?cat=motion">Motion Graphics</a></li>
          <li><a href="<?= SITE_URL ?>/?cat=wp">WP Themes</a></li>
          <li><a href="<?= SITE_URL ?>/?cat=custom">Custom Sites</a></li>
        </ul>
      </div>
      <div>
        <div class="footer__title">Exam Prep</div>
        <ul class="footer__links">
          <li><a href="<?= SITE_URL ?>/?cat=mcq">MCQ Bundles</a></li>
          <li><a href="<?= SITE_URL ?>/?cat=lms">LMS Courses</a></li>
        </ul>
      </div>
      <div>
        <div class="footer__title">Company</div>
        <ul class="footer__links">
          <li><a href="https://dgtmint.com">DGT Mint</a></li>
          <li><a href="https://digitruinx.com">Digitruinx</a></li>
          <li><a href="<?= SITE_URL ?>/account/">My Account</a></li>
          <li><a href="mailto:connect@dgtmint.com">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="footer__bottom">
      <p>© 2026 Digitruinx Pvt Ltd · market.dgtmint.com · All rights reserved</p>
      <p>GST invoices available · Secured by Razorpay</p>
    </div>
  </div>
</footer>
<?php if (!empty($extra_foot)) echo $extra_foot; ?>
</body>
</html>
