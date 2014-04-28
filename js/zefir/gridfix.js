varienGrid.prototype.rowMouseClick = function(event) {
  //prevent executing event when clicking on interactive element itself
  if(['a', 'input', 'select', 'option'].indexOf(Event.element(event).tagName.toLowerCase())!=-1) {
    return;
  }
  
  //original code
  if (this.rowClickCallback) {
    try {
      this.rowClickCallback(this, event);
    }
    catch (e) {
    }
  }
  varienGlobalEvents.fireEvent('gridRowClick', event);
}