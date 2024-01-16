
export function navigateTo($page, $tag) {
    const c = document.querySelectorAll($tag);

    c.forEach(card => card.addEventListener("click", () => {
        window.location.href = `${$page}?cardId=${card.children[0].dataset.card}`;
    }));
}

export function sendSelectTagChangeWithForm($selectTag, $formTag) {
    const select = document.querySelector($selectTag);
    const form = document.querySelector($formTag);

    select.addEventListener('change', function () {
        form.submit();
    });
}
