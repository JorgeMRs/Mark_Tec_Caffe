document.addEventListener("DOMContentLoaded", function () {
    const categoryDetails = document.getElementById("category-details");
    let categoriesData = null;  // Variable para almacenar las categorías
    let productsData = {};      // Variable para almacenar productos por categoría

    function scrollToCategoryDetails() {
        setTimeout(() => {
            const targetElement = document.querySelector("#category-details");
            if (targetElement) {
                const targetRect = targetElement.getBoundingClientRect();
                const offset = window.innerHeight / 2 - targetRect.height / 2;
                window.scrollTo({
                    top: targetRect.top + window.pageYOffset - offset,
                    behavior: 'smooth'
                });
            }
        }, 100);
    }

    // Function to load categories
    function loadCategories(selectedCategory) {
        if (categoriesData) {
            renderCategories(selectedCategory);
        } else {
            fetch('/src/db/getCategories.php')
                .then(response => response.json())
                .then(categories => {
                    categoriesData = categories;
                    renderCategories(selectedCategory);
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Render categories
    function renderCategories(selectedCategory) {
        const sidebar = document.getElementById('sidebar');
        let categoriesHtml = "";

        categoriesData.forEach(category => {
            const isSelected = category.nombre === selectedCategory ? " selected" : "";
            categoriesHtml += `
            <div class="category-item${isSelected}" data-category="${category.nombre}" data-category-id="${category.idCategoria}">
                <img src="${category.imagen}" alt="${category.nombre}">
                <p>${category.nombre}</p>
            </div>
            `;
        });

        sidebar.innerHTML = `
            <h2>Nuestras categorías</h2>
            ${categoriesHtml}
        `;

        addSidebarEvents();
    }

    // Function to load products
    function loadProducts(category, idCategoria) {
        if (productsData[category]) {
            renderProducts(productsData[category], category);
        } else {
            fetch(`/src/db/product.php?idCategoria=${idCategoria}`)
                .then(response => response.json())
                .then(data => {
                    productsData[category] = data;
                    renderProducts(data, category);
                })
                .catch(error => console.error("Error:", error));
        }
    }

    // Render products
    function renderProducts(data, category) {
        let productosHtml = "";
        data.forEach(producto => {
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
                <!-- Categories will be loaded here -->
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

        // Ensure categories are loaded
        loadCategories(category);
    }

    // Function to add events to sidebar categories
    function addSidebarEvents() {
        const categories = document.querySelectorAll(".sidebar .category-item");

        categories.forEach((category) => {
            category.addEventListener("click", function () {
                const sidebarCategory = this.getAttribute("data-category");
                const sidebarIdCategoria = this.getAttribute("data-category-id");

                categories.forEach((item) => item.classList.remove("selected"));
                this.classList.add("selected");

                loadProducts(sidebarCategory, sidebarIdCategoria);

                localStorage.setItem('selectedCategory', JSON.stringify({ category: sidebarCategory, idCategoria: sidebarIdCategoria }));

                window.location.hash = 'category-details';
                scrollToCategoryDetails();
            });
        });
    }

    // Retrieve the saved category and show it if available
    function initializePage() {
        const savedCategory = localStorage.getItem('selectedCategory');

        if (savedCategory) {
            const { category, idCategoria } = JSON.parse(savedCategory);
            loadProducts(category, idCategoria);

            // Load categories dynamically
            loadCategories(category);

            window.location.hash = 'category-details';
            scrollToCategoryDetails();
        } else {
            const defaultCategory = "Café Especiales";
            const defaultIdCategoria = "1";
            loadProducts(defaultCategory, defaultIdCategoria);

            // Load categories dynamically
            loadCategories(defaultCategory);

            window.location.hash = 'category-details';
            scrollToCategoryDetails();
        }
    }

    initializePage();

    // Restore scroll position and apply zoom effect if coming back from product page
    if (sessionStorage.getItem('scrollPosition')) {
        setTimeout(() => {
            window.scrollTo({
                top: sessionStorage.getItem('scrollPosition'),
                behavior: 'smooth'
            });

            sessionStorage.removeItem('scrollPosition');
        }, 300);
    }

    // Add events to the product items on the page
    const productItems = document.querySelectorAll(".product-item");

    productItems.forEach((item) => {
        item.addEventListener("click", function () {
            const category = this.getAttribute("data-category");
            const idCategoria = this.getAttribute("data-category-id");

            localStorage.setItem('selectedCategory', JSON.stringify({ category, idCategoria }));

            categoryDetails.style.display = "block";
            loadProducts(category, idCategoria);

            window.location.hash = 'category-details';
            scrollToCategoryDetails();
        });
    });
});
