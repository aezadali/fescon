// Fescon International LLC - Interactive JS
document.addEventListener('DOMContentLoaded', () => {
  
  // Mobile Menu Toggle with Backdrop
  const mobileToggle = document.getElementById('mobileToggle');
  const navMenu = document.getElementById('navMenu');

  // Create backdrop overlay for mobile nav
  const navBackdrop = document.createElement('div');
  navBackdrop.className = 'nav-backdrop';
  document.body.appendChild(navBackdrop);

  function openMobileNav() {
    navMenu.classList.add('active');
    navBackdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
    const icon = mobileToggle.querySelector('i');
    if (icon) { icon.classList.replace('fa-bars', 'fa-times'); }
  }

  function closeMobileNav() {
    navMenu.classList.remove('active');
    navBackdrop.classList.remove('active');
    document.body.style.overflow = '';
    const icon = mobileToggle.querySelector('i');
    if (icon) { icon.classList.replace('fa-times', 'fa-bars'); }
  }

  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', () => {
      navMenu.classList.contains('active') ? closeMobileNav() : openMobileNav();
    });

    navBackdrop.addEventListener('click', closeMobileNav);
  }

  // Smooth Scroll for Nav Links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        if (navMenu && navMenu.classList.contains('active')) {
          closeMobileNav();
        }
        targetElement.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

  // Multi-Section Parallax Scroll Engine
  const parallaxSections = document.querySelectorAll('.hero-section, .about-section, .values-section, .leadership-section, .services-section, .certifications-section, .projects-section, .contact-section');

  function handleParallax() {
    const windowHeight = window.innerHeight;

    parallaxSections.forEach(section => {
      const rect = section.getBoundingClientRect();
      if (rect.top < windowHeight && rect.bottom > 0) {
        // Calculate relative position within viewport
        const speed = 0.35;
        const yPos = -(rect.top * speed);
        section.style.backgroundPositionY = `calc(50% + ${yPos}px)`;
      }
    });
  }

  window.addEventListener('scroll', () => {
    window.requestAnimationFrame(handleParallax);
  });
  handleParallax();

  // Portfolio Continuous Infinite Marquee & Drag-to-Scroll Control
  const marqueeWrapper = document.getElementById('projectsMarqueeWrapper');
  const projectsTrack = document.getElementById('projectsTrack');
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projPrevBtn = document.getElementById('projPrevBtn');
  const projNextBtn = document.getElementById('projNextBtn');

  if (marqueeWrapper && projectsTrack) {
    const originalCards = Array.from(projectsTrack.querySelectorAll('.project-card'));
    let isCloned = false;
    let isAutoScrolling = true;
    let isDragging = false;
    let startX = 0;
    let startScrollLeft = 0;
    let isPaused = false;
    let pauseTimeout = null;
    let currentFilter = 'all';
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';

    // Clone unique cards in JS for seamless infinite wrap
    function initClones() {
      if (isCloned) return;
      originalCards.forEach(card => {
        const clone = card.cloneNode(true);
        clone.classList.add('clone-card');
        projectsTrack.appendChild(clone);
      });
      isCloned = true;
    }

    initClones();

    // Auto-scroll loop
    const scrollSpeed = 0.8;
    function autoScroll() {
      if (currentFilter === 'all' && isAutoScrolling && !isPaused && !isDragging) {
        const halfWidth = projectsTrack.scrollWidth / 2;
        if (halfWidth > 0) {
          if (!isRTL) {
            marqueeWrapper.scrollLeft += scrollSpeed;
            if (marqueeWrapper.scrollLeft >= halfWidth) {
              marqueeWrapper.scrollLeft -= halfWidth;
            }
          } else {
            marqueeWrapper.scrollLeft -= scrollSpeed;
            if (Math.abs(marqueeWrapper.scrollLeft) >= halfWidth) {
              marqueeWrapper.scrollLeft += halfWidth;
            }
          }
        }
      }
      requestAnimationFrame(autoScroll);
    }
    requestAnimationFrame(autoScroll);

    // Pause on hover
    marqueeWrapper.addEventListener('mouseenter', () => { isPaused = true; });
    marqueeWrapper.addEventListener('mouseleave', () => {
      if (!isDragging) {
        clearTimeout(pauseTimeout);
        isPaused = false;
      }
    });

    // Drag to scroll (mouse)
    marqueeWrapper.addEventListener('mousedown', (e) => {
      isDragging = true;
      isPaused = true;
      marqueeWrapper.classList.add('is-dragging');
      startX = e.pageX - marqueeWrapper.offsetLeft;
      startScrollLeft = marqueeWrapper.scrollLeft;
    });

    window.addEventListener('mouseup', () => {
      if (isDragging) {
        isDragging = false;
        marqueeWrapper.classList.remove('is-dragging');
        clearTimeout(pauseTimeout);
        pauseTimeout = setTimeout(() => { isPaused = false; }, 1500);
      }
    });

    marqueeWrapper.addEventListener('mousemove', (e) => {
      if (!isDragging) return;
      e.preventDefault();
      const x = e.pageX - marqueeWrapper.offsetLeft;
      const walk = (x - startX) * 1.5;
      marqueeWrapper.scrollLeft = startScrollLeft - walk;

      if (currentFilter === 'all') {
        const halfWidth = projectsTrack.scrollWidth / 2;
        if (halfWidth > 0) {
          if (marqueeWrapper.scrollLeft >= halfWidth) {
            marqueeWrapper.scrollLeft -= halfWidth;
            startScrollLeft -= halfWidth;
          } else if (marqueeWrapper.scrollLeft <= 0) {
            marqueeWrapper.scrollLeft += halfWidth;
            startScrollLeft += halfWidth;
          }
        }
      }
    });

    // Touch support (mobile / touch devices)
    marqueeWrapper.addEventListener('touchstart', () => {
      isPaused = true;
    }, { passive: true });

    marqueeWrapper.addEventListener('touchend', () => {
      clearTimeout(pauseTimeout);
      pauseTimeout = setTimeout(() => { isPaused = false; }, 1500);
    }, { passive: true });

    // Prev / Next button controls
    if (projPrevBtn) {
      projPrevBtn.addEventListener('click', () => {
        isPaused = true;
        const scrollStep = window.innerWidth <= 480 ? 280 : (window.innerWidth <= 768 ? 305 : 380);
        const scrollDelta = isRTL ? scrollStep : -scrollStep;
        marqueeWrapper.scrollBy({ left: scrollDelta, behavior: 'smooth' });
        clearTimeout(pauseTimeout);
        pauseTimeout = setTimeout(() => { isPaused = false; }, 2500);
      });
    }

    if (projNextBtn) {
      projNextBtn.addEventListener('click', () => {
        isPaused = true;
        const scrollStep = window.innerWidth <= 480 ? 280 : (window.innerWidth <= 768 ? 305 : 380);
        const scrollDelta = isRTL ? -scrollStep : scrollStep;
        marqueeWrapper.scrollBy({ left: scrollDelta, behavior: 'smooth' });
        clearTimeout(pauseTimeout);
        pauseTimeout = setTimeout(() => { isPaused = false; }, 2500);
      });
    }

    // Category Filtering
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        currentFilter = btn.getAttribute('data-filter');
        const allCards = projectsTrack.querySelectorAll('.project-card');

        if (currentFilter === 'all') {
          allCards.forEach(card => {
            card.style.display = 'block';
            card.style.opacity = '1';
          });
          projectsTrack.style.width = 'max-content';
          projectsTrack.style.justifyContent = 'flex-start';
          projectsTrack.style.flexWrap = 'nowrap';
          isAutoScrolling = true;
          isPaused = false;
        } else {
          isAutoScrolling = false;
          isPaused = true;
          allCards.forEach(card => {
            if (card.classList.contains('clone-card')) {
              card.style.display = 'none';
            } else if (card.getAttribute('data-category') === currentFilter) {
              card.style.display = 'block';
              card.style.opacity = '1';
            } else {
              card.style.display = 'none';
            }
          });
          projectsTrack.style.width = '100%';
          projectsTrack.style.justifyContent = 'center';
          projectsTrack.style.flexWrap = 'wrap';
          marqueeWrapper.scrollLeft = 0;
        }
      });
    });
  }

  // Modal Viewer (Leadership Card & Certificates)
  const modalOverlay = document.getElementById('modalOverlay');
  const modalTitle = document.getElementById('modalTitle');
  const modalBody = document.getElementById('modalBody');
  const modalClose = document.getElementById('modalClose');

  window.openModal = function(title, mediaSrc, isPdf = false) {
    if (!modalOverlay || !modalBody) return;
    modalTitle.textContent = title;
    
    if (isPdf) {
      modalBody.innerHTML = `<iframe src="${mediaSrc}" style="width:100%; height:70vh; border:none;"></iframe>`;
    } else {
      modalBody.innerHTML = `<img src="${mediaSrc}" alt="${title}" style="max-width:100%; border-radius:12px;">`;
    }
    
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  if (modalClose) {
    modalClose.addEventListener('click', closeModal);
  }

  if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeModal();
    });
  }

  function closeModal() {
    if (modalOverlay) {
      modalOverlay.classList.remove('active');
      document.body.style.overflow = 'auto';
    }
  }

  // Contact Form AJAX Handler
  const contactForm = document.getElementById('contactForm');
  const formAlert = document.getElementById('formAlert');
  const submitBtn = document.getElementById('submitBtn');
  const submitBtnOriginalHTML = submitBtn ? submitBtn.innerHTML : '';

  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(contactForm);
      const isArabic = document.documentElement.getAttribute('lang') === 'ar';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>' + (isArabic ? 'جاري الإرسال...' : 'Processing...') + '</span>';
      }

      fetch('contact_submit.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (formAlert) {
          formAlert.style.display = 'block';
          if (data.status === 'success') {
            formAlert.className = 'form-alert success';
            formAlert.textContent = data.message;
            contactForm.reset();
          } else {
            formAlert.className = 'form-alert error';
            formAlert.textContent = data.message;
          }
        }
      })
      .catch(error => {
        if (formAlert) {
          formAlert.style.display = 'block';
          formAlert.className = 'form-alert error';
          formAlert.textContent = isArabic
            ? 'حدث خطأ غير متوقع. يرجى الاتصال بنا مباشرة على 9919 9919 968+.'
            : 'An unexpected error occurred. Please call +968 9919 9710 directly.';
        }
      })
      .finally(() => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = submitBtnOriginalHTML;
        }
      });
    });
  }

  // Scroll Reveal Animations
  const revealElements = document.querySelectorAll(
    '.section-tag, .section-title, .section-subtitle, .about-text, .about-visual, ' +
    '.value-card, .executive-card, .service-card, .cert-card, .project-card, ' +
    '.mv-card, .about-branch-box, .tagline-banner, .contact-info-box, .contact-form-box, ' +
    '.why-card, .why-motto-banner'
  );

  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const delay = entry.target.dataset.revealDelay || 0;
        setTimeout(() => {
          entry.target.classList.add('revealed');
        }, delay);
        revealObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  });

  revealElements.forEach((el, index) => {
    el.classList.add('reveal-on-scroll');
    const parent = el.parentElement;
    if (parent) {
      const siblings = Array.from(parent.children).filter(c => 
        revealElements.length && c.matches('.value-card, .executive-card, .service-card, .cert-card, .project-card, .mv-card')
      );
      const siblingIndex = siblings.indexOf(el);
      if (siblingIndex > 0) {
        el.dataset.revealDelay = siblingIndex * 100;
      }
    }
    revealObserver.observe(el);
  });

  // Active nav link tracking on scroll
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  const navObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        navLinks.forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === '#' + entry.target.id) {
            link.classList.add('active');
          }
        });
      }
    });
  }, {
    threshold: 0.3,
    rootMargin: '-100px 0px -50% 0px'
  });

  sections.forEach(section => navObserver.observe(section));

});
