const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"noticias.check-slug":{"uri":"noticias\/check-slug\/{slug}","methods":["GET","HEAD"],"parameters":["slug"]},"noticias.index":{"uri":"noticias","methods":["GET","HEAD"]},"noticias.create":{"uri":"noticias\/create","methods":["GET","HEAD"]},"noticias.edit":{"uri":"noticias\/edit","methods":["GET","HEAD"]},"noticias.store":{"uri":"noticias","methods":["POST"]},"noticias.show":{"uri":"noticias\/{noticia}","methods":["GET","HEAD"],"parameters":["noticia"],"bindings":{"noticia":"id"}},"categorias.index":{"uri":"categorias","methods":["GET","HEAD"]},"tags.index":{"uri":"tags","methods":["GET","HEAD"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
