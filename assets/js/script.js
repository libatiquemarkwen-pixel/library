var registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
        var pass = document.getElementById('password').value;
        var confirm = document.getElementById('confirm').value;
        var msg = document.getElementById('errorMsg');

        if (pass.length < 6) {
            msg.textContent = 'Password must be at least 6 characters.';
            e.preventDefault();
        } else if (pass !== confirm) {
            msg.textContent = 'Passwords do not match.';
            e.preventDefault();
        }
    });
}
var searchBox = document.getElementById('searchBox');
var cards = document.querySelectorAll('.book-card');
var onlyAvail = document.getElementById('onlyAvail');
var activeCat = '';

function applyFilters() {
    var text = searchBox.value.toLowerCase();
    var shown = 0;

    cards.forEach(function (card) {
        var ok = card.textContent.toLowerCase().indexOf(text) > -1
            && (activeCat === '' || card.dataset.cat === activeCat)
            && (!onlyAvail || !onlyAvail.checked || card.dataset.avail === '1');
        card.style.display = ok ? '' : 'none';
        if (ok) { shown++; }
    });

    var none = document.getElementById('noResults');
    if (none && cards.length > 0) {
        none.textContent = 'No books match your search.';
        none.style.display = shown === 0 ? 'block' : 'none';
    }
}

if (searchBox) {
    searchBox.addEventListener('input', applyFilters);
    if (onlyAvail) { onlyAvail.addEventListener('change', applyFilters); }
    document.querySelectorAll('#chips .chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            activeCat = chip.dataset.cat;
            document.querySelectorAll('#chips .chip').forEach(function (c) { c.classList.toggle('is-active', c === chip); });
            applyFilters();
        });
    });
    applyFilters();
}

function showMessage(type, text) {
    var box = document.getElementById('message');
    if (!box) { return; }
    var div = document.createElement('div');
    div.className = 'alert alert-' + type;
    div.textContent = text;
    box.innerHTML = '';
    box.appendChild(div);
}

var borrowButtons = document.querySelectorAll('.btn-borrow');
borrowButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = btn.dataset.id;
        var data = new FormData();
        data.append('book_id', id);

        fetch('borrow.php', { method: 'POST', body: data })
            .then(function (res) { return res.json(); })
            .then(function (result) {
                if (result.ok) {
                    showMessage('success', result.message);

                    var pill = document.getElementById('copies-' + id);
                    var n = result.copies;
                    pill.textContent = n < 1 ? 'None left' : n + (n === 1 ? ' copy' : ' copies');
                    pill.className = 'pill ' + (n < 1 ? 'pill-out' : (n <= 1 ? 'pill-low' : 'pill-ok'));

                    var card = btn.closest('.book-card');
                    if (card) { card.dataset.avail = n < 1 ? '0' : '1'; }

                    btn.textContent = 'Borrowed';
                    btn.className = 'btn btn-sm btn-secondary';
                    btn.disabled = true;
                } else {
                    showMessage('danger', result.message);
                }
            })
            .catch(function () {
                alert('Something went wrong. Please try again.');
            });
    });
});

if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            if (en.isIntersecting) {
                en.target.classList.add('in');
                io.unobserve(en.target);
                setTimeout(function () { en.target.classList.remove('reveal', 'in'); }, 800);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.feature, .stat, .book-card, .band-grid > div').forEach(function (el, i) {
        el.style.transitionDelay = (i % 4) * 70 + 'ms';
        el.classList.add('reveal');
        io.observe(el);
    });
}
