document.addEventListener("DOMContentLoaded", function () {
    const mainCategory = document.getElementById("main-category");
    const categoryDetails = document.getElementById("category-details");

    function scrollToCategoryDetails() {
        // Espera a que el contenido se haya cargado
        setTimeout(() => {
            const targetElement = document.querySelector("#category-details");
            if (targetElement) {
                // Calcula la posición deseada para el centro
                const targetRect = targetElement.getBoundingClientRect();
                const offset = window.innerHeight / 2 - targetRect.height / 2;
                window.scrollTo({
                    top: targetRect.top + window.pageYOffset - offset,
                    behavior: 'smooth'
                });
            }
        }, 100); // Pequeña espera para asegurar que el contenido esté cargado
    }
  
        const toggleButton = document.querySelector('.toggle-button');
        const navLinks = document.querySelector('.nav-links');
        const dropdownLinks = document.querySelectorAll('.dropdown-link');
    
        // Evento para mostrar/ocultar el menú de navegación
        toggleButton.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    
        // Evento para mostrar/ocultar el menú desplegable en móviles
        dropdownLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = link.parentElement;
                dropdown.classList.toggle('active');
            });
        });
    
        // Agregar eventos a las categorías dentro del dropdown de la vista móvil
        const mobileCategoryDropdown = document.querySelector('#mobile-category-dropdown');
        const mobileCategories = mobileCategoryDropdown.querySelectorAll('a');
    
        mobileCategories.forEach(category => {
            category.addEventListener('click', function() {
                const categoryName = this.getAttribute('data-category');
                const categoryId = this.getAttribute('data-category-id');
    
                // Guardar la categoría seleccionada en localStorage
                localStorage.setItem('selectedCategory', JSON.stringify({ category: categoryName, idCategoria: categoryId }));
    
                // Ocultar categorías principales y mostrar los detalles
                document.getElementById('main-category').style.display = 'none';
                document.getElementById('category-details').style.display = 'block';
    
                // Cargar productos para la categoría seleccionada
                loadProducts(categoryName, categoryId);
    
                // Establecer el fragmento de URL para el desplazamiento
                window.location.hash = 'category-details';
                scrollToCategoryDetails();
            });
        });
    // Función para cargar productos
    function loadProducts(category, idCategoria) {
        fetch(`/src/db/product.php?idCategoria=${idCategoria}`)
            .then((response) => response.json())
            .then((data) => {
                let productosHtml = "";
                data.forEach((producto) => {
                    const precio = parseFloat(producto.precio);
                    productosHtml += `
                    <div class="product-item">
                        <a href="productos.php?id=${producto.idProducto}" class="image-link">
                            <div class="image-container">
                                <img src="${producto.imagen}" alt="${producto.nombre}">
                            </div>
                        </a>
                        <h3>${producto.nombre}</h3>
                        <span class="precio">€${precio.toFixed(2)}</span>
                    </div>
                    `;
                });

                categoryDetails.innerHTML = `
                <h1>${category.replace(/-/g, " ")}</h1>
                <div class="main-content">
                    <div class="sidebar" id="sidebar">
                        <h2>Nuestras categorías</h2>
                        <div class="category-item${category === "Cafés Especiales" ? " selected" : ""}" data-category="Cafés Especiales" data-category-id="1">
                            <img src="/public/assets/img/categorias/cafe-especiales.jpg" alt="Cafés Especiales">
                            <p>Cafés Especiales</p>
                        </div>
                        <div class="category-item${category === "Cafés con Leche" ? " selected" : ""}" data-category="Cafés con Leche" data-category-id="2">
                            <img src="/public/assets/img/categorias/cafe-con-leche.jpg" alt="Café con Leche">
                            <p>Cafés con Leche</p>
                        </div>
                        <div class="category-item${category === "Cafés Fríos" ? " selected" : ""}" data-category="Cafés Fríos" data-category-id="3">
                            <img src="/public/assets/img/categorias/cafe-frio.jpg" alt="Cafés Fríos">
                            <p>Cafés Fríos</p>
                        </div>
                        <div class="category-item${category === "Pasteles y Postres" ? " selected" : ""}" data-category="Pasteles y Postres" data-category-id="4">
                            <img src="/public/assets/img/categorias/pastel-y-tortas.jpg" alt="Postres">
                            <p>Pasteles y Postres</p>
                        </div>
                        <div class="category-item${category === "Té" ? " selected" : ""}" data-category="Té" data-category-id="5">
                            <img src="/public/assets/img/categorias/tipos-de-te.jpg" alt="Té">
                            <p>Té</p>
                        </div>
                        <div class="category-item${category === "Sandwich y Bocadillos" ? " selected" : ""}" data-category="Sandwich y Bocadillos" data-category-id="6">
                            <img src="/public/assets/img/categorias/bocadillo.jpg" alt="Sandwiches">
                            <p>Sandwiches y Bocadillos</p>
                        </div>
                    </div>
                    <div class="product-grid">
                        ${productosHtml}
                    </div>
                </div>
            `;

            const productGrid = categoryDetails.querySelector('.product-grid');
            const productItems = productGrid.children;
            const itemsPerRow = 3; 
            const totalRows = Math.ceil(productItems.length / itemsPerRow);

            if (totalRows > 2) {
                document.getElementById('sidebar').classList.add('sticky-sidebar');
            }

                // Añadir eventos a las categorías del sidebar
                addSidebarEvents();
            })
            .catch((error) => console.error("Error:", error));
    }

    // Definir productItems
    const productItems = document.querySelectorAll(".product-item");

    // Eventos para las categorías principales
    productItems.forEach((item) => {
        item.addEventListener("click", function () {
            const category = this.getAttribute("data-category");
            const idCategoria = this.getAttribute("data-category-id");

            // Guardar la categoría seleccionada en localStorage
            localStorage.setItem('selectedCategory', JSON.stringify({ category, idCategoria }));

            // Ocultar categorías principales y mostrar los detalles
            mainCategory.style.display = "none";
            categoryDetails.style.display = "block";

            // Cargar productos para la categoría seleccionada
            loadProducts(category, idCategoria);

            // Establecer el fragmento de URL para el desplazamiento
            window.location.hash = 'category-details';
            scrollToCategoryDetails();
        });
    });

    // Función para añadir eventos a las categorías del sidebar
    function addSidebarEvents() {
        const categories = document.querySelectorAll(".sidebar .category-item");

        categories.forEach((category) => {
            category.addEventListener("click", function () {
                const sidebarCategory = this.getAttribute("data-category");
                const sidebarIdCategoria = this.getAttribute("data-category-id");

                // Actualizar el estado visual
                categories.forEach((item) => item.classList.remove("selected"));
                this.classList.add("selected");

                // Cargar productos para la categoría seleccionada desde el sidebar
                loadProducts(sidebarCategory, sidebarIdCategoria);

                // Guardar la categoría seleccionada en localStorage
                localStorage.setItem('selectedCategory', JSON.stringify({ category: sidebarCategory, idCategoria: sidebarIdCategoria }));

                // Establecer el fragmento de URL para el desplazamiento
                window.location.hash = 'category-details';
                scrollToCategoryDetails();
            });
        });
    }

    // Inicializar eventos en las categorías del sidebar
    addSidebarEvents();

    // Recuperar la categoría guardada y mostrarla solo si la navegación proviene de productos.php
    const referrer = document.referrer;
    const savedCategory = localStorage.getItem('selectedCategory');

    if (savedCategory && referrer.includes("productos.php")) {
        const { category, idCategoria } = JSON.parse(savedCategory);
        mainCategory.style.display = "none";
        categoryDetails.style.display = "block";
        loadProducts(category, idCategoria);

        // Establecer el fragmento de URL para el desplazamiento
        window.location.hash = 'category-details';
        scrollToCategoryDetails();
    } else {
        mainCategory.style.display = "block";
        categoryDetails.style.display = "none";
        localStorage.removeItem('selectedCategory'); // Limpiar el localStorage si no proviene de productos.php
    }
    
});
