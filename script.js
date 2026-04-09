const buttons = document.querySelectorAll('.nav-but');
const content = document.querySelectorAll('.content');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        //remove active class from all buttons
        buttons.forEach(but => but.classList.remove('active'));
        button.classList.add('active');

        //show the content that matches the button's data-target
        const target = button.getAttribute('data-target');
        content.forEach(content => {
            if(content.id === target) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        });
    });
});

const paymentModal = document.getElementById("paymentModal");
const openPayment = document.getElementById("openPayment");
const closeBut = document.querySelector(".close-but");


openPayment.addEventListener("click", () => {
    paymentModal.style.display = "flex";
});


closeBut.addEventListener("click", () => {
    paymentModal.style.display = "none";
});


window.addEventListener("click" , (e) => {
    if (e.target === paymentModal) {
        paymentModal.style.display = "none";
    }
});

let basket = [];

const addButtons = document.querySelectorAll(".add-but");
const basketList = document.getElementById("basketList");
const totalPrice = document.getElementById("totalPrice");


addButtons.forEach(but => {
    but.addEventListener("click", () => {
        const name = but.getAttribute("data-name");
        const price = parseFloat(but.getAttribute("data-price"));

        basket.push({ name, price });
        updateBasket();
    });
});

function updateBasket() {
    basketList.innerHTML = "";
    let total = 0;

    basket.forEach(item => {
        const li = document.createElement("li");
        li.textContent = `${item.name} - ${item.price}₾`;
        basketList.appendChild(li);
        
        total += item.price;
    });

    totalPrice.textContent = `Total: ${total.toFixed(2)}₾`;
}

function filterProducts(category){
    const products = document.querySelectorAll('.product-card');

    products.forEach(product => {

        const productCategory = product.dataset.category;


        if(category === 'all' || productCategory === category){
            product.style.display = 'block';
        }
        else if(product.dataset.category === category){
            product.style.display = 'block';
        }  
        else {
            product.style.display = 'none';
        }
    });
}

const searchInput = document.querySelector('.search-box');

searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const product = document.querySelectorAll('.product-card');

    product.forEach(product => {
        const name = product.querySelector('.product-caption').textContent.toLowerCase();

        if (name.includes(searchTerm)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none'
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const homeBtn = document.getElementById('homeShopBtn');

    if(homeBtn) {
        homeBtn.addEventListener('click', () => {
            const shopNavButton = document.querySelector('.nav-but[data-target="shop"]');
            if (shopNavButton) {
                shopNavButton.click();
            }
        });
    }
});

basketList.innerHTML += `
    <div class="basket-item">
        <span>${itemName}</span>
        <strong>₾${itemPrice}</strong>
    </div>
`;