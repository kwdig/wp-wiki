/* JavaScriptなしでもメニューを表示。スマホ時のみ開閉します。 */
(() => {
  const button = document.querySelector('.menu-toggle');
  const navigation = document.getElementById('site-navigation');
  if (!button || !navigation) return;
  document.documentElement.classList.add('js');
  const mobile = window.matchMedia('(max-width: 600px)');
  const setOpen = (open) => {
    button.setAttribute('aria-expanded', String(open));
    navigation.hidden = mobile.matches && !open;
  };
  const reset = () => setOpen(!mobile.matches);
  button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && mobile.matches && !navigation.hidden) {
      setOpen(false);
      button.focus();
    }
  });
  if (mobile.addEventListener) mobile.addEventListener('change', reset);
  else mobile.addListener(reset);
  reset();
})();
