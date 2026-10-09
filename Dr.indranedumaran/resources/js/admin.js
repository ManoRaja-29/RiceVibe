import Alpine from 'alpinejs';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

window.Alpine = Alpine;

const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const uploadUrl = document.querySelector('meta[name="media-upload-url"]')?.content;

async function uploadImage(file) {
    const body = new FormData();
    body.append('file', file);
    const res = await fetch(uploadUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
        body,
    });
    if (!res.ok) throw new Error('Upload failed');
    return (await res.json()).url;
}

Alpine.data('richEditor', (initial) => ({
    html: initial,
    init() {
        const quill = new Quill(this.$refs.editor, {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: [
                        [{ header: [2, 3, 4, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['blockquote', 'link', 'image', 'video'],
                        ['clean'],
                    ],
                    handlers: {
                        image: () => {
                            const input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'image/jpeg,image/png,image/webp';
                            input.onchange = async () => {
                                if (!input.files[0]) return;
                                try {
                                    const url = await uploadImage(input.files[0]);
                                    const range = quill.getSelection(true);
                                    quill.insertEmbed(range.index, 'image', url);
                                } catch (e) {
                                    alert('Image upload failed. Use JPG, PNG or WebP under 5 MB.');
                                }
                            };
                            input.click();
                        },
                    },
                },
            },
        });
        quill.root.innerHTML = this.html;
        quill.on('text-change', () => {
            this.html = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
        });
    },
}));

Alpine.start();
