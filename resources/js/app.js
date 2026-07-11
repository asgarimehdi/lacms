import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Paragraph from '@editorjs/paragraph';
import List from '@editorjs/list';
import Image from '@editorjs/image';
import Quote from '@editorjs/quote';
import Embed from '@editorjs/embed';

// ponytail: no framework wrapper, raw Editor.js. Image upload via fetch to /admin/upload-image.
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-editorjs]').forEach(el => {
        const inputId = el.dataset.editorjs;
        const input = document.querySelector(`input[wire\\:model="${inputId}"]`)
            || document.querySelector(`[x-data] input[wire\\:model="${inputId}"]`)
            || el.previousElementSibling;

        if (!input || !input.name && !input.getAttribute('wire:model')) {
            // fallback to id match
            const byId = document.getElementById(inputId);
            if (!byId) return;
        }

        const targetInput = document.querySelector(`input[wire\\:model="${inputId}"]`)
            || document.getElementById(inputId);

        if (!targetInput) return;

        const editor = new EditorJS({
            holder: el,
            tools: {
                header: { class: Header, config: { levels: [2, 3, 4], defaultLevel: 2 } },
                paragraph: { class: Paragraph },
                list: { class: List, inlineToolbar: true },
                image: {
                    class: Image,
                    config: {
                        uploader: {
                            uploadByFile(file) {
                                const fd = new FormData();
                                fd.append('image', file);
                                return fetch('/admin/upload-image', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
                                    body: fd,
                                }).then(r => r.json()).then(d => ({ success: d.success ? 1 : 0, file: { url: d.url } }));
                            },
                        },
                    },
                },
                quote: { class: Quote, inlineToolbar: true },
                embed: { class: Embed },
            },
            data: targetInput.value ? JSON.parse(targetInput.value) : { blocks: [] },
            async onChange() {
                targetInput.value = JSON.stringify(await editor.save());
                targetInput.dispatchEvent(new Event('input', { bubbles: true }));
            },
        });

        el._editor = editor;
    });
});