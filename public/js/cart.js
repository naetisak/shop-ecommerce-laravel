class ManageCart {
    _key = 'mcart';
    _subtotal = 0;

    add(product_variant_id, qty) {
        product_variant_id = parseInt(product_variant_id);
        qty = parseInt(qty);

        let cartItems = this._getItems();

        if (cartItems == null) {
            cartItems = { [product_variant_id]: qty }
        } else {
            if (cartItems[product_variant_id]) {
                cartItems[product_variant_id] += qty;
            } else {
                cartItems = {
                    ...cartItems,
                    [product_variant_id]: qty
                }
            }
        }

        localStorage.setItem(this._key, JSON.stringify(cartItems));
    }

    getQty(product_variant_id) {
        let cartItems = this._getItems();
        if (cartItems == null) return 0;

        if (!cartItems[product_variant_id]) return 0;

        return cartItems[product_variant_id];
    }

    manageQty(e, product_variant_id, qty, stock) {
        let currentQty = this.getQty(product_variant_id) ?? 0;
        let newQty = currentQty + qty;

        if (newQty > stock) {
            cuteToast({
                type: 'info',
                message: "Sorry! You can't add more quantity."
            })
            return;
        }

        if (newQty == 0) return;

        this.add(product_variant_id, qty);
        e.parentElement.querySelector('span').textContent = newQty;

        let pTag = e.parentElement.parentElement.previousElementSibling;
        pTag.querySelector('.qty').textContent = newQty;
        pTag.querySelector('.itemTotalPrice').textContent = pTag.querySelector('.itemPrice').textContent * newQty;
        this.updatePrice();
    }

    isInCart(product_variant_id) {
        let cartItems = this._getItems();

        if (cartItems != null) {
            if (cartItems[product_variant_id]) {
                return true;
            }
        }

        return false;
    }

    remove(product_variant_id) {
        let cartItems = this._getItems();
        if (cartItems != null) {
            delete cartItems[product_variant_id];
            localStorage.setItem(this._key, JSON.stringify(cartItems));
        }
        this.updatePrice();
    }

    empty() {
        localStorage.removeItem(this._key);
    }

    _getItems() {
        return JSON.parse(localStorage.getItem(this._key));
    }

    updatePrice() {
        let subtotalElement = document.getElementById('subtotal');
        let totalElement = document.getElementById('total');

        let items = document.getElementById('itemContainer').querySelectorAll('.itemTotalPrice');
        this._subtotal = 0;
        items.forEach(item => {
            this._subtotal += parseFloat(item.textContent);
        });
        subtotalElement.textContent = this._subtotal;
        totalElement.textContent = this._subtotal;

        document.getElementById('discount_code').value = '';
        document.getElementById('discount_amount').textContent = 0;
        document.getElementById('discount_msg').textContent = 0;
    }

    getSubTotal() {
        return this._subtotal;
    }
}

const mCart = new ManageCart();