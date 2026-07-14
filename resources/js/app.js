// ponytail: replace Editor.js with Quill CDN. Handles image paste/upload via /admin/upload-image.
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-quill]').forEach(el => {
        const inputId = el.dataset.quill;
        const targetInput = document.getElementById(inputId)
            || document.querySelector(`input[wire\\:model="${inputId}"]`);

        if (!targetInput) return;

        const quill = new Quill(el, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean'],
                ],
            },
        });

        if (targetInput.value) {
            quill.clipboard.dangerouslyPasteHTML(targetInput.value);
        }

        quill.on('text-change', () => {
            targetInput.value = quill.root.innerHTML;
            targetInput.dispatchEvent(new Event('input', { bubbles: true }));
        });

        // Image upload handler for toolbar image button
        el.addEventListener('click', (e) => {
            if (e.target.closest('.ql-image')) {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.onchange = () => {
                    if (!input.files?.[0]) return;
                    const formData = new FormData();
                    formData.append('image', input.files[0]);
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                    fetch('/admin/upload-image', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf },
                        body: formData,
                    }).then(r => r.json()).then(d => {
                        if (d.success && d.url) {
                            const range = quill.getSelection(true);
                            quill.insertEmbed(range.index, 'image', d.url);
                        }
                    });
                };
                input.click();
            }
        });
    });
});