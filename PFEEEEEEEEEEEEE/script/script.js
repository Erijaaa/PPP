document.addEventListener("DOMContentLoaded", function () {
  const menuItems = document.querySelectorAll(".menu-item");
  const contentSections = document.querySelectorAll(".main-content");

  menuItems.forEach((item) => {
    item.addEventListener("click", function () {
      menuItems.forEach((menuItem) => menuItem.classList.remove("active"));
      contentSections.forEach((section) => section.classList.remove("active"));

      this.classList.add("active");

      const sectionId = this.id + "-content";
      document.getElementById(sectionId).classList.add("active");
    });
  });

  const addDocumentBtn = document.getElementById("add-document");
  const addPropertyBtn = document.getElementById("add-property");
  const documentsTable = document
    .getElementById("documents-table")
    .querySelector("tbody");
  const propertyTable = document
    .getElementById("property-table")
    .querySelector("tbody");

  if (addDocumentBtn) {
    addDocumentBtn.addEventListener("click", function () {
      const rowCount = documentsTable.rows.length;
      const newRow = documentsTable.insertRow();
      newRow.innerHTML = `
                        <td>${rowCount + 1}</td>
                        <td class="document-cell"><span class="document-link">إضافة وثيقة</span></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><button class="btn-delete">حذف</button></td>
                    `;

      newRow
        .querySelector(".btn-delete")
        .addEventListener("click", function () {
          documentsTable.removeChild(newRow);
          renumberRows(documentsTable);
        });

      setupDocumentCellListeners();
    });
  }

  if (addPropertyBtn) {
    addPropertyBtn.addEventListener("click", function () {
      const rowCount = propertyTable.rows.length;
      const newRow = propertyTable.insertRow();
      newRow.innerHTML = `
                        <td>${rowCount + 1}</td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><button class="btn-delete">حذف</button></td>
                    `;

      newRow
        .querySelector(".btn-delete")
        .addEventListener("click", function () {
          propertyTable.removeChild(newRow);
          renumberRows(propertyTable);
        });
    });
  }

  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      const tbody = row.parentNode;
      tbody.removeChild(row);
      renumberRows(tbody);
    });
  });

  function renumberRows(tbody) {
    const rows = tbody.rows;
    for (let i = 0; i < rows.length; i++) {
      rows[i].cells[0].textContent = i + 1;
    }
  }

  const modal = document.getElementById("documentModal");
  const closeBtn = document.querySelector(".close");
  const saveBtn = document.getElementById("saveDocument");
  const cancelBtn = document.getElementById("cancelDocument");

  function setupDocumentCellListeners() {
    const documentCells = document.querySelectorAll(".document-cell");
    documentCells.forEach((cell) => {
      cell.addEventListener("click", function () {
        modal.style.display = "block";
        modal.dataset.currentCell = this.cellIndex;
        modal.dataset.currentRow = this.parentElement.rowIndex;
      });
    });
  }

  closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  if (saveBtn) {
    saveBtn.addEventListener("click", function () {
      const documentName = document.getElementById("document-name").value;
      if (documentName) {
        const currentRow = modal.dataset.currentRow;
        const documentsTable = document.getElementById("documents-table");
        if (currentRow && documentsTable.rows[currentRow]) {
          documentsTable.rows[currentRow].cells[1].innerHTML = (
            <span class="document-link">${documentName}</span>
          );
          setupDocumentCellListeners();
        }
      }
      modal.style.display = "none";
    });
  }

  if (cancelBtn) {
    cancelBtn.addEventListener("click", function () {
      modal.style.display = "none";
    });
  }

  setupDocumentCellListeners();

  const addPropertyBurdensBtn = document.querySelector(
    "#property-burdens-content button:nth-child(2)"
  );
  if (addPropertyBurdensBtn) {
    addPropertyBurdensBtn.addEventListener("click", function () {
      const tbody = document.querySelector("#property-burdens-content tbody");
      const rowCount = tbody.rows.length;
      const newRow = tbody.insertRow();

      for (let i = 0; i < 12; i++) {
        const cell = newRow.insertCell();
        if (i === 0) {
          cell.textContent = rowCount + 1;
        } else {
          cell.textContent = "";
        }
      }
    });
  }
});
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("openModalBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function () {
  modal.style.display = "block";
};

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

// Handle form submission
document.getElementById("identityForm").onsubmit = function (e) {
  e.preventDefault();
  // Here you can add code to handle the form data
  alert("تم حفظ بيانات وثيقة الهوية بنجاح!");
  modal.style.display = "none";
};
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

//wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
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

  // Add agent to list
  function addAgentToList(name, email) {
    const agentsList = document.getElementById("agentsList");
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

  // Global functions for agent management
  window.clearForm = function () {
    document.getElementById("agentName").value = "";
    document.getElementById("agentEmail").value = "";
  };

  window.editAgent = function (button) {
    const agentItem = button.closest(".agent-item");
    const name = agentItem.querySelector("strong").textContent;
    const email = agentItem.querySelector(".agent-email").textContent;

    document.getElementById("agentName").value = name;
    document.getElementById("agentEmail").value = email;

    // You can add more edit functionality here
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

//wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
// Agent form submission
const agentForm = document.getElementById("agentForm");
agentForm.addEventListener("submit", async function (e) {
  e.preventDefault();

  const post = document.getElementById("post").value; // Rôle (1 ou 2)
  const name = document.getElementById("agentName").value;
  const cin = document.getElementById("cin").value;
  const email = document.getElementById("agentEmail").value;
  const password = document.getElementById("password").value;

  if (post && name && cin && email && password) {
    // Convertir la valeur en rôle (1 = redacteur, 2 = valideur)
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
        addAgentToList(name, cin, email, role);
        clearForm();
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

// Fonction pour charger les agents existants au chargement de la page
async function loadAgents() {
  try {
    const response = await fetch("connect.php?action=get_agents");
    const agents = await response.json();

    const agentsList = document.getElementById("agentsList");
    agentsList.innerHTML = ""; // Vider la liste actuelle

    agents.forEach((agent) => {
      addAgentToList(agent.name, agent.cin, agent.email, agent.role);
    });
  } catch (error) {
    console.error("Erreur lors du chargement des agents:", error);
  }
}

// Charger les agents au démarrage
document.addEventListener("DOMContentLoaded", loadAgents);

// Fonction pour ajouter un agent à la liste visuellement
function addAgentToList(name, cin, email, role) {
  const agentsList = document.getElementById("agentsList");
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
            <button class="btn btn-small btn-edit" onclick="editAgent(this)">تعديل</button>
            <button class="btn btn-small btn-delete" onclick="deleteAgent(this)">حذف</button>
        </div>
    `;

  agentsList.appendChild(agentItem);
}

// Fonction pour supprimer un agent
window.deleteAgent = async function (button) {
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

// Fonction pour vider le formulaire
window.clearForm = function () {
  document.getElementById("post").value = "";
  document.getElementById("agentName").value = "";
  document.getElementById("cin").value = "";
  document.getElementById("agentEmail").value = "";
  document.getElementById("password").value = "";
};

// Fonction pour modifier un agent (non implémentée ici, mais ajoutée pour référence)
window.editAgent = function (button) {
  const agentItem = button.closest(".agent-item");
  const name = agentItem.querySelector("strong").textContent;
  const cin = agentItem.querySelector(".agent-cin").textContent;
  const email = agentItem.querySelector(".agent-email").textContent;
  const roleText = agentItem
    .querySelector("strong")
    .nextSibling.textContent.includes("رئيس التحرير")
    ? "1"
    : "2";

  document.getElementById("post").value = roleText;
  document.getElementById("agentName").value = name;
  document.getElementById("cin").value = cin;
  document.getElementById("agentEmail").value = email;
  // Note : Le mot de passe n'est pas rempli pour des raisons de sécurité
  alert("يمكنك الآن تعديل بيانات المستخدم");
};
// Variable pour suivre l'email de l'agent en cours de modification
let editingEmail = null;

// Fonction pour modifier un agent
window.editAgent = function (button) {
  const agentItem = button.closest(".agent-item");
  const name = agentItem.querySelector("strong").textContent;
  const cin = agentItem.querySelector(".agent-cin").textContent;
  const email = agentItem.querySelector(".agent-email").textContent;
  const roleText = agentItem
    .querySelector("strong")
    .nextSibling.textContent.includes("رئيس التحرير")
    ? "1"
    : "2";

  document.getElementById("post").value = roleText;
  document.getElementById("agentName").value = name;
  document.getElementById("cin").value = cin;
  document.getElementById("agentEmail").value = email;
  document.getElementById("password").value = ""; // Ne pas remplir le mot de passe pour des raisons de sécurité

  editingEmail = email; // Stocker l'email pour savoir quel agent modifier
  alert("يمكنك الآن تعديل بيانات المستخدم");
};

// Agent form submission (mise à jour pour gérer ajout et modification)
const agentForm = document.getElementById("agentForm");
agentForm.addEventListener("submit", async function (e) {
  e.preventDefault();

  const post = document.getElementById("post").value;
  const name = document.getElementById("agentName").value;
  const cin = document.getElementById("cin").value;
  const email = document.getElementById("agentEmail").value;
  const password = document.getElementById("password").value;

  if (post && name && cin && email && password) {
    const role = post === "1" ? "redacteur" : post === "2" ? "valideur" : "";

    if (!role) {
      alert("يرجى إدخال 1 للرئيس التحرير أو 2 للمحقق في حقل عدد الصلاحية.");
      return;
    }

    try {
      // Déterminer si c'est un ajout ou une modification
      const action = editingEmail ? "update_agent" : "add_agent";
      const body = editingEmail
        ? `action=${action}&role=${role}&oldEmail=${encodeURIComponent(
            editingEmail
          )}&name=${encodeURIComponent(name)}&cin=${encodeURIComponent(
            cin
          )}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(
            password
          )}`
        : `action=${action}&role=${role}&name=${encodeURIComponent(
            name
          )}&cin=${encodeURIComponent(cin)}&email=${encodeURIComponent(
            email
          )}&password=${encodeURIComponent(password)}`;

      const response = await fetch("connect.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: body,
      });

      const result = await response.json();

      if (result.success) {
        // Recharger la liste des agents
        loadAgents();
        clearForm();
        editingEmail = null; // Réinitialiser après modification
        alert(
          editingEmail ? "تم تعديل المستخدم بنجاح!" : "تم إضافة المستخدم بنجاح!"
        );
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
