document.addEventListener("DOMContentLoaded", function () {
  // ====== Menu Navigation ======
  const menuItems = document.querySelectorAll(".menu-item");
  const contentSections = document.querySelectorAll(".main-content");

  menuItems.forEach((item) => {
    item.addEventListener("click", function () {
      menuItems.forEach((menuItem) => menuItem.classList.remove("active"));
      contentSections.forEach((section) => section.classList.remove("active"));

      this.classList.add("active");

      const sectionId = this.id + "-content";
      const targetSection = document.getElementById(sectionId);
      if (targetSection) {
        targetSection.classList.add("active");
      }
    });
  });

  // ====== Documents Table ======
  const addDocumentBtn = document.getElementById("add-document");
  const documentsTable = document.getElementById("documents-table");
  let documentsTableBody = null;

  if (documentsTable) {
    documentsTableBody = documentsTable.querySelector("tbody");
  }

  if (addDocumentBtn && documentsTableBody) {
    addDocumentBtn.addEventListener("click", function () {
      const rowCount = documentsTableBody.rows.length;
      const newRow = documentsTableBody.insertRow();
      newRow.innerHTML = `
        <td>${rowCount + 1}</td>
        <td class="document-cell"><span class="document-link">إضافة وثيقة</span></td>
        <td><input type="text"></td>
        <td><input type="text"></td>
        <td><input type="text"></td>
        <td><input type="text"></td>
        <td><button class="btn-delete">حذف</button></td>
      `;

      const deleteBtn = newRow.querySelector(".btn-delete");
      if (deleteBtn) {
        deleteBtn.addEventListener("click", function () {
          documentsTableBody.removeChild(newRow);
          renumberRows(documentsTableBody);
        });
      }

      setupDocumentCellListeners();
    });
  }

  // ====== Delete Buttons ======
  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      if (row) {
        const tbody = row.parentNode;
        if (tbody) {
          tbody.removeChild(row);
          renumberRows(tbody);
        }
      }
    });
  });

  // ====== Modal Handling ======
  const myModal = document.getElementById("myModal");
  const openModalBtn = document.getElementById("openModalBtn");
  const closeModalBtns = document.getElementsByClassName("close");
  const identityForm = document.getElementById("identityForm");

  // Modal open/close handlers
  if (myModal && openModalBtn && closeModalBtns.length > 0) {
    openModalBtn.addEventListener("click", function() {
      myModal.style.display = "block";
    });

    closeModalBtns[0].addEventListener("click", function() {
      myModal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
      if (event.target === myModal) {
        myModal.style.display = "none";
      }
    });
  }

  // Identity form handler
  if (identityForm) {
    identityForm.addEventListener("submit", function(e) {
      e.preventDefault();
      alert("تم حفظ بيانات وثيقة الهوية بنجاح!");
      if (myModal) {
        myModal.style.display = "none";
      }
    });
  }

  // ====== Helper Functions ======
  function renumberRows(tbody) {
    if (!tbody) return;
    const rows = tbody.rows;
    for (let i = 0; i < rows.length; i++) {
      if (rows[i].cells[0]) {
        rows[i].cells[0].textContent = i + 1;
      }
    }
  }

  function setupDocumentCellListeners() {
    const documentCells = document.querySelectorAll(".document-cell");
    const modal = document.getElementById("documentModal");
    
    documentCells.forEach((cell) => {
      cell.addEventListener("click", function () {
        if (modal) {
          modal.style.display = "block";
          modal.dataset.currentCell = this.cellIndex;
          modal.dataset.currentRow = this.parentElement.rowIndex;
        }
      });
    });
  }

  // ====== Message Functions ======
  window.showMessage = function() {
    const messageElement = document.getElementById("message");
    const overlayElement = document.getElementById("overlay");
    
    if (messageElement) messageElement.style.display = "block";
    if (overlayElement) overlayElement.style.display = "block";
    return false;
  };

  window.hideMessage = function() {
    const messageElement = document.getElementById("message");
    const overlayElement = document.getElementById("overlay");
    
    if (messageElement) messageElement.style.display = "none";
    if (overlayElement) overlayElement.style.display = "none";
  };

  // ====== Contract Functions ======
  window.saveContract = function() {
    alert("تم حفظ العقد بنجاح");
    console.log("تم حفظ العقد");
  };

  // Initial setup
  setupDocumentCellListeners();
});

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("openModalBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Only add event listeners if elements exist
if (btn && modal && span) {
  btn.onclick = function () {
    modal.style.display = "block";
  };

  span.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
}

// Handle form submission
document.getElementById("identityForm").onsubmit = function (e) {
  e.preventDefault();
  // Here you can add code to handle the form data
  alert("تم حفظ بيانات وثيقة الهوية بنجاح!");
  modal.style.display = "none";
}; 

function showMessage() {
  document.getElementById("message").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  return false; // Important !
}

function hideMessage() {
  document.getElementById("message").style.display = "none";
  document.getElementById("overlay").style.display = "none";
}

function showMessage() {
  alert("تم حفظ البيانات بنجاح في قاعدة البيانات");
  return true; 
}

// Script pour ajouter une nouvelle ligne au tableau
document.addEventListener("DOMContentLoaded", function () {
  const ajouterPieceBtn = document.getElementById("ajouter-piece");

  if (ajouterPieceBtn) {
    ajouterPieceBtn.addEventListener("click", function () {
      // Récupérer l'ID de la demande depuis l'attribut data
      const idDemande = this.getAttribute("data-id-demande");

      const tbody = document.querySelector("#documents-table tbody");
      const rowCount = tbody.rows.length;
      const newRow = document.createElement("tr");

      newRow.innerHTML = `
              <td>${rowCount + 1}</td>
              <td><input type="text" name="lib_pieces[]" /></td>
              <td><input type="text" name="date_document[]" /></td>
              <td><input type="text" name="ref_inscription[]" /></td>
              <td><input type="text" name="date_ref[]" /></td>
              <td><input type="text" name="codepiece[]" /></td>
              <input type="hidden" name="id_demande[]" value="${idDemande}" />
          `;

      tbody.appendChild(newRow);
    });
  }
});

function saveContract() {
  alert("تم حفظ العقد بنجاح");
  console.log("تم حفظ العقد");
}

document.addEventListener("DOMContentLoaded", function () {
  // Menu navigation functionality
  const menuItems = document.querySelectorAll(".menu-item");
  const contentSections = document.querySelectorAll(".content-section");

  menuItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault();

      // Remove active class from all menu items
      menuItems.forEach((menuItem) => menuItem.classList.remove("active"));

      // Hide all content sections
      contentSections.forEach((section) => section.classList.remove("active"));

      // Add active class to clicked menu item
      this.classList.add("active");

      // Show corresponding content section
      const sectionId = this.getAttribute("data-section") + "-content";
      document.getElementById(sectionId).classList.add("active");
    });
  });

  // Agent form submission
  const agentForm = document.getElementById("agentForm");
  if (agentForm) {
    agentForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const name = document.getElementById("agentName").value;
      const email = document.getElementById("agentEmail").value;

      if (name && email) {
        addAgentToList(name, email);
        clearForm();
        alert("تم إضافة الوكيل بنجاح!");
      }
    });
  }

  // Add agent to list
  function addAgentToList(name, email) {
    const agentsList = document.getElementById("agentsList");
    if (agentsList) {
      const agentItem = document.createElement("div");
      agentItem.className = "agent-item";

      agentItem.innerHTML = `
            <div class="agent-info">
                <strong>${name}</strong>
                <span class="agent-email">${email}</span>
            </div>
            <div class="agent-actions">
                <button class="btn btn-small btn-edit" onclick="editAgent(this)">تعديل</button>
                <button class="btn btn-small btn-delete" onclick="deleteAgent(this)">حذف</button>
            </div>
        `;

      agentsList.appendChild(agentItem);
    }
  }

  // Global functions for agent management
  window.clearForm = function () {
    const agentName = document.getElementById("agentName");
    const agentEmail = document.getElementById("agentEmail");
    if (agentName) agentName.value = "";
    if (agentEmail) agentEmail.value = "";
  };

  window.editAgent = function (button) {
    const agentItem = button.closest(".agent-item");
    const name = agentItem.querySelector("strong").textContent;
    const email = agentItem.querySelector(".agent-email").textContent;

    const agentName = document.getElementById("agentName");
    const agentEmail = document.getElementById("agentEmail");
    if (agentName) agentName.value = name;
    if (agentEmail) agentEmail.value = email;

    alert("يمكنك الآن تعديل بيانات الوكيل");
  };

  window.deleteAgent = function (button) {
    if (confirm("هل أنت متأكد من حذف هذا الوكيل؟")) {
      const agentItem = button.closest(".agent-item");
      agentItem.remove();
      alert("تم حذف الوكيل بنجاح!");
    }
  };
});

// Agent form submission for the enhanced version
const agentFormEnhanced = document.getElementById("agentForm");
if (agentFormEnhanced) {
  agentFormEnhanced.addEventListener("submit", async function (e) {
    e.preventDefault();

    const post = document.getElementById("post").value;
    const name = document.getElementById("agentName").value;
    const cin = document.getElementById("cin").value;
    const email = document.getElementById("agentEmail").value;
    const password = document.getElementById("password").value;

    if (post && name && cin && email && password) {
      // Convert value to role (1 = redacteur, 2 = valideur)
      const role = post === "1" ? "redacteur" : post === "2" ? "valideur" : "";

      if (!role) {
        alert("يرجى إدخال 1 للرئيس التحرير أو 2 للمحقق في حقل عدد الصلاحية.");
        return;
      }

      try {
        const response = await fetch("connect.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `action=add_agent&role=${role}&name=${encodeURIComponent(
            name
          )}&cin=${encodeURIComponent(cin)}&email=${encodeURIComponent(
            email
          )}&password=${encodeURIComponent(password)}`,
        });

        const result = await response.json();

        if (result.success) {
          addAgentToListEnhanced(name, cin, email, role);
          clearFormEnhanced();
          alert("تم إضافة المستخدم بنجاح!");
        } else {
          alert("حدث خطأ: " + result.message);
        }
      } catch (error) {
        console.error("Erreur:", error);
        alert("حدث خطأ أثناء الاتصال بالخادم.");
      }
    } else {
      alert("يرجى ملء جميع الحقول.");
    }
  });
}

// Function to load existing agents on page load
async function loadAgents() {
  try {
    const response = await fetch("connect.php?action=get_agents");
    const agents = await response.json();

    const agentsList = document.getElementById("agentsList");
    if (agentsList) {
      agentsList.innerHTML = ""; // Clear current list

      agents.forEach((agent) => {
        addAgentToListEnhanced(agent.name, agent.cin, agent.email, agent.role);
      });
    }
  } catch (error) {
    console.error("Erreur lors du chargement des agents:", error);
  }
}

// Load agents on startup
document.addEventListener("DOMContentLoaded", loadAgents);

// Function to add an agent to the list visually
function addAgentToListEnhanced(name, cin, email, role) {
  const agentsList = document.getElementById("agentsList");
  if (agentsList) {
    const agentItem = document.createElement("div");
    agentItem.className = "agent-item";

    const roleText = role === "redacteur" ? "رئيس التحرير" : "محقق";
    agentItem.innerHTML = `
          <div class="agent-info">
              <strong>${name}</strong> <span style="color: gray;">(${roleText})</span>
              <span class="agent-cin">${cin}</span>
              <span class="agent-email">${email}</span>
          </div>
          <div class="agent-actions">
              <button class="btn btn-small btn-edit" onclick="editAgentEnhanced(this)">تعديل</button>
              <button class="btn btn-small btn-delete" onclick="deleteAgentEnhanced(this)">حذف</button>
          </div>
      `;

    agentsList.appendChild(agentItem);
  }
}

// Function to delete an agent
window.deleteAgentEnhanced = async function (button) {
  if (confirm("هل أنت متأكد من حذف هذا المستخدم؟")) {
    const agentItem = button.closest(".agent-item");
    const email = agentItem.querySelector(".agent-email").textContent;
    const role = agentItem
      .querySelector("strong")
      .nextSibling.textContent.includes("رئيس التحرير")
      ? "redacteur"
      : "valideur";

    try {
      const response = await fetch("connect.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `action=delete_agent&role=${role}&email=${encodeURIComponent(
          email
        )}`,
      });

      const result = await response.json();

      if (result.success) {
        agentItem.remove();
        alert("تم حذف المستخدم بنجاح!");
      } else {
        alert("حدث خطأ أثناء حذف المستخدم: " + result.message);
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("حدث خطأ أثناء الاتصال بالخادم.");
    }
  }
};

// Function to clear the form
window.clearFormEnhanced = function () {
  const post = document.getElementById("post");
  const agentName = document.getElementById("agentName");
  const cin = document.getElementById("cin");
  const agentEmail = document.getElementById("agentEmail");
  const password = document.getElementById("password");

  if (post) post.value = "";
  if (agentName) agentName.value = "";
  if (cin) cin.value = "";
  if (agentEmail) agentEmail.value = "";
  if (password) password.value = "";
};

// Variable to track the email of the agent being edited
let editingEmail = null;

// Function to edit an agent
window.editAgentEnhanced = function (button) {
  const agentItem = button.closest(".agent-item");
  const name = agentItem.querySelector("strong").textContent;
  const cin = agentItem.querySelector(".agent-cin").textContent;
  const email = agentItem.querySelector(".agent-email").textContent;
  const roleText = agentItem
    .querySelector("strong")
    .nextSibling.textContent.includes("رئيس التحرير")
    ? "1"
    : "2";

  const post = document.getElementById("post");
  const agentName = document.getElementById("agentName");
  const cinInput = document.getElementById("cin");
  const agentEmail = document.getElementById("agentEmail");
  const password = document.getElementById("password");

  if (post) post.value = roleText;
  if (agentName) agentName.value = name;
  if (cinInput) cinInput.value = cin;
  if (agentEmail) agentEmail.value = email;
  if (password) password.value = ""; // Don't fill password for security reasons

  editingEmail = email; // Store email to know which agent to modify
  alert("يمكنك الآن تعديل بيانات المستخدم");
};

//ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ
function showEditForm(button, userData) {
  const row = button.closest("tr");
  const nextRow = row.nextElementSibling;

  // Masquer tous les autres formulaires
  document
    .querySelectorAll(".edit-form-row")
    .forEach((r) => (r.style.display = "none"));

  // Afficher ce formulaire
  nextRow.style.display = "table-row";

  // Préremplir les champs
  const form = nextRow.querySelector("form");
  form.agentName.value = (userData.nom || "") + " " + (userData.prenom || "");
  form.cin.value = userData.identification_number || "";
  form.agentEmail.value = userData.email || "";
  form.agentAdresse.value = userData.adresse || "";
  form.agentTele.value = userData.telephone || "";
  form.agentNaissance.value = userData.date_naissance || "";
  form.password.value = ""; // On ne préremplit pas le mot de passe pour la sécurité
}

function hideEditForm(button) {
  const row = button.closest("tr");
  row.style.display = "none";
}
///////////////TEXT REFUS
function submitObjection() {
  const objectionText = document.getElementById("objectionText").value;
  if (objectionText.trim() === "") {
    alert("يرجى كتابة نص الاعتراض قبل الإرسال.");
    return;
  }
  fetch("listeContrats.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ objection: objectionText }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Réponse du serveur :", data);
      closeModal();
    })
    .catch((error) => console.error("Erreur :", error));
}
