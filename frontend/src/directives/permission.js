import { useRBAC } from '@/composables/useRBAC';

export default {
  mounted(el, binding) {
    const { hasPermission } = useRBAC();
    const { value } = binding;
    
    if (value && !hasPermission(value)) {
      // If the element has no permission, remove it from the DOM
      el.parentNode?.removeChild(el);
    }
  },
  updated(el, binding) {
    const { hasPermission } = useRBAC();
    const { value } = binding;
    
    if (value && hasPermission(value)) {
      // If permission is granted and element was previously hidden, add it back
      if (!document.body.contains(el)) {
        // Find the original position if possible
        const originalPosition = el._originalPosition;
        if (originalPosition?.parentNode) {
          originalPosition.parentNode.insertBefore(el, originalPosition.nextSibling);
        }
      }
    } else if (document.body.contains(el)) {
      // Store the original position before removing
      el._originalPosition = document.createComment(`v-permission: ${value}`);
      el.parentNode?.replaceChild(el._originalPosition, el);
    }
  },
  beforeUnmount(el) {
    // Clean up any references
    if (el._originalPosition) {
      el._originalPosition = null;
    }
  }
};
