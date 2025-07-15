document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  // LOGIN
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        const res = await fetch("/api/login", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, password }),
        });

        const data = await res.json();

        if (res.ok) {
          localStorage.setItem("token", data.token);
          localStorage.setItem("role", data.user.role);

          if (data.user.role === "admin") {
            window.location.href = "dashboard_admin.html";
          } else {
            window.location.href = "dashboard_user.html";
          }
        } else {
          alert(data.msg || "Error al iniciar sesión");
        }
      } catch (err) {
        alert("Error de conexión");
      }
    });
  }

  // REGISTRO
  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        const res = await fetch("/api/register", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ name, email, password }),
        });

        const data = await res.json();

        if (res.ok) {
          alert("Registro exitoso, inicia sesión.");
          window.location.href = "index.html";
        } else {
          alert(data.msg || "Error al registrar");
        }
      } catch (err) {
        alert("Error de conexión");
      }
    });
  }

  // PROTECCIÓN DE RUTAS PRIVADAS (solo si hay token)
  const privatePages = [
    "dashboard_admin.html",
    "dashboard_user.html",
    "tareas.html",
    "personajes.html",
  ];
  const currentPage = window.location.pathname.split("/").pop();

  if (privatePages.includes(currentPage)) {
    const token = localStorage.getItem("token");
    if (!token) {
      window.location.href = "index.html";
      return;
    }

    // Lógica para obtener datos del usuario y mostrarlos en pantalla
    fetch("/api/me", {
      headers: {
        Authorization: "Bearer " + token,
      },
    })
      .then((res) => res.json())
      .then((data) => {
        if (!data.user) {
          logout();
        }

        const user = data.user;

        // Mostrar nombre y rol si existen los elementos
        const userName = document.getElementById("user-name");
        const userRole = document.getElementById("user-role");
        if (userName) userName.textContent = user.name;
        if (userRole) userRole.textContent = user.role;

        // Mostrar herramientas según rol
        const adminTools = document.getElementById("admin-tools");
        const userTools = document.getElementById("user-tools");

        if (adminTools)
          adminTools.style.display =
            user.role === "admin" ? "block" : "none";
        if (userTools)
          userTools.style.display =
            user.role === "user" ? "block" : "none";
      })
      .catch(() => {
        logout(); // Por si el token es inválido
      });
  }
});

// LOGOUT GLOBAL
function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("role");
  window.location.href = "index.html";
}
