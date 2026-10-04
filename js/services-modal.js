// ===== Modal for a Single Service =====

// Get service cards
const cards = Array.from(document.querySelectorAll('.service-card'));

// Options for each service (adjust as needed)
const optionsByService = {
  "IT Support": ["Install Software", "Update Software", "Network Support", "Password Reset"],
  "Facility Maintenance": ["Electrical", "Plumbing", "AC", "Furniture Repair"],
  "Logistics Services": ["Transport Equipment", "Document Delivery", "Vehicle Arrangement"],
  "Documents & Records": ["Archiving", "Letter Issuance", "Data Update"],
  "Hospitality Services": ["Event Setup", "Coffee/Tea Service", "Guest Reception"],
  "Cleaning Services": ["Office Cleaning", "Sanitization", "Waste Collection"],
  "Technical Support": ["Computer Repair", "Network Support", "Data Recovery"],
  "Translation Services": ["Text Translation", "Simultaneous Translation", "Document Editing"],
  "Consulting Services": ["IT Consulting", "Marketing Consulting", "Business Development Consulting"],
  "Electrical Maintenance": ["Lighting Systems", "Generator Repair", "Wiring Repair"],
  "Training & Development": ["Programming Courses", "Management Courses", "Design Courses"]
};

// Modal elements (ensure the HTML has matching IDs/Classes)
const backdrop   = document.getElementById('svcModalBackdrop');
const form       = document.getElementById('svcForm');
const closeBtn   = document.getElementById('svcCloseBtn');
const cancelBtn  = document.getElementById('svcCancelBtn');
const svcTitle   = document.getElementById('svcCurrentService');
const subArea    = document.getElementById('svcSubOptionsArea');

let currentService = null;
const picked = new Set();

// Open the modal when clicking on a service card
cards.forEach(card => {
  const svcName = card.dataset.service || card.querySelector('h2')?.textContent?.trim() || 'Service';
  card.style.cursor = 'pointer';
  card.addEventListener('click', () => openModalFor(svcName));
});

function openModalFor(serviceName) {
  currentService = serviceName;
  picked.clear();

  // Set the service title in the modal
  if (svcTitle) svcTitle.textContent = serviceName;

  // Render the service options
  renderSubOptions(serviceName);

  // Show the modal
  backdrop.style.display = 'flex';
  backdrop.setAttribute('aria-hidden', 'false');
  document.getElementById('svcName')?.focus();

  // Close the modal quickly
  backdrop.addEventListener('click', closeOnBackdropOnce);
  document.addEventListener('keydown', escCloseOnce);
}

function closeModal() {
  backdrop.style.display = 'none';
  backdrop.setAttribute('aria-hidden', 'true');
  backdrop.removeEventListener('click', closeOnBackdropOnce);
  document.removeEventListener('keydown', escCloseOnce);
}

function closeOnBackdropOnce(e) {
  if (e.target === backdrop) closeModal();
}

function escCloseOnce(e) {
  if (e.key === 'Escape') closeModal();
}

closeBtn?.addEventListener('click', closeModal);
cancelBtn?.addEventListener('click', closeModal);

function renderSubOptions(serviceName) {
  subArea.innerHTML = '';
  const opts = optionsByService[serviceName] || [];

  if (!opts.length) {
    const note = document.createElement('div');
    note.textContent = 'No options available for this service.';
    note.style.opacity = '.7';
    subArea.appendChild(note);
    return;
  }

  opts.forEach(label => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'svc-subbtn';
    btn.textContent = label;
    btn.setAttribute('aria-pressed', 'false');

    btn.addEventListener('click', () => {
      const active = btn.getAttribute('aria-pressed') === 'true';
      if (active) {
        btn.setAttribute('aria-pressed', 'false');
        const t = btn.querySelector('.tick'); if (t) t.remove();
        picked.delete(label);
      } else {
        btn.setAttribute('aria-pressed', 'true');
        if (!btn.querySelector('.tick')) {
          const t = document.createElement('span'); t.className = 'tick'; t.textContent = '✓';
          btn.prepend(t);
        }
        picked.add(label);
      }
    });

    subArea.appendChild(btn);
  });
}

// Submit form (AJAX)
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const name   = form.name.value.trim();
  const office = form.office.value.trim();

  if (!name || !office || picked.size === 0) {
    alert('Please fill in your name, office number, and select at least one option from the service choices.');
    return;
  }

  // إعداد البيانات لإرسالها إلى الخادم
  const formData = new FormData();
  formData.append('service_type', currentService);
  formData.append('name', name);
  formData.append('office', office);
  formData.append('options', Array.from(picked).join(', '));

  try {
    const response = await fetch('php/services.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      alert(`Request submitted successfully!\nServer Message: ${result.message}`);
      form.reset();
      closeModal();
    } else {
      alert(`Error submitting request: ${result.message}`);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('An unexpected error occurred while submitting your request.');
  }
});
