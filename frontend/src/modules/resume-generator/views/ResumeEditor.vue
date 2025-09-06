<template>
  <div class="resume-editor">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-semibold">
        {{ $t("resume.generator.edit_resume") }}
      </h2>
      <Toolbar
        :snap-size="canvas.snapSize"
        @add-text="onAddText"
        @add-rect="onAddRect"
        @image="onAddImage"
        @bring-forward="onBringForward"
        @send-backward="onSendBackward"
        @delete="onDelete"
        @zoom-in="() => setZoom(canvas.zoom + 0.1)"
        @zoom-out="() => setZoom(canvas.zoom - 0.1)"
        @zoom-reset="() => setZoom(1)"
        @toggle-grid="toggleGrid"
        @toggle-snap="toggleSnap"
        @toggle-guides="toggleGuides"
        @cycle-snap-size="cycleSnapSize"
        @center-x="centerX"
        @center-y="centerY"
        @duplicate="duplicateSelected"
        @preview="previewResume"
        @export-pdf="exportPdf"
      />
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
      <div class="lg:col-span-2 space-y-4">
        <ComponentsPalette />
        <ElementsPanel />
      </div>
      <div class="lg:col-span-8">
        <CanvasBoard />
      </div>
      <div class="lg:col-span-2">
        <InspectorPanel />
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import jsPDF from "jspdf";
import { useRouter } from "vue-router";
import { useResumeStore } from "../store";
import Toolbar from "../components/Toolbar.vue";
import CanvasBoard from "../components/CanvasBoard.vue";
import ElementsPanel from "../components/ElementsPanel.vue";
import ComponentsPalette from "../components/ComponentsPalette.vue";
import InspectorPanel from "../components/InspectorPanel.vue";

export default {
  name: "ResumeEditor",
  components: {
    Toolbar,
    CanvasBoard,
    ElementsPanel,
    ComponentsPalette,
    InspectorPanel,
  },
  setup() {
    const store = useResumeStore();
    const router = useRouter();

    const canvas = computed(() => store.canvas);

    function setZoom(z) {
      store.setZoom(z);
    }
    function toggleGrid() {
      store.toggleGrid();
    }
    function toggleSnap() {
      store.toggleSnap();
    }
    function toggleGuides() {
      store.toggleGuides();
    }
    function cycleSnapSize() {
      store.cycleSnapSize();
    }
    function onAddText() {
      store.addElement({
        type: "text",
        width: 420,
        height: 60,
        props: { text: "Text", fontSize: 24, fill: "#111827" },
      });
    }
    function onAddRect() {
      store.addElement({
        type: "rect",
        width: 300,
        height: 120,
        props: { fill: "#E5E7EB", radius: 8 },
      });
    }
    function onAddImage(file) {
      const reader = new FileReader();
      reader.onload = () =>
        store.addElement({
          type: "image",
          width: 180,
          height: 180,
          props: { src: reader.result },
        });
      reader.readAsDataURL(file);
    }

    function onBringForward() {
      const id = store.canvas.selectedElementId;
      if (id) store.bringForward(id);
    }
    function onSendBackward() {
      const id = store.canvas.selectedElementId;
      if (id) store.sendBackward(id);
    }
    function onDelete() {
      const id = store.canvas.selectedElementId;
      if (id) store.removeElement(id);
    }

    function centerX() {
      store.centerSelectedX();
    }
    function centerY() {
      store.centerSelectedY();
    }
    function duplicateSelected() {
      const id = store.canvas.selectedElementId;
      if (id) store.duplicateElement(id);
    }

    function previewResume() {
      router.push({ name: "resume-preview" });
    }
    async function exportPdf() {
      const { width, height, elements } = store.canvas;
      // Create true A4 document in millimeters
      const doc = new jsPDF({ unit: "mm", format: "a4" });
      const pageWidthMm = 210;
      const pageHeightMm = 297;
      //const pageWidthPt = 72 * (pageWidthMm / 25.4)
      const pageHeightPt = 72 * (pageHeightMm / 25.4);
      // Scale factors: px -> mm
      const sx = pageWidthMm / width;
      const sy = pageHeightMm / height;

      // Optional: try to use embedded Unicode font if available (for RU/PL). See README note.
      if (window.__fontData) {
        doc.addFileToVFS("Inter-Regular.ttf", window.__fontData.regular);
        doc.addFont("Inter-Regular.ttf", "Inter", "normal");
        doc.addFileToVFS("Inter-Bold.ttf", window.__fontData.bold);
        doc.addFont("Inter-Bold.ttf", "Inter", "bold");
      }

      const hexToRgb = (hex) => {
        const s = hex?.replace("#", "") || "000000";
        const bigint = parseInt(
          s.length === 3
            ? s
                .split("")
                .map((c) => c + c)
                .join("")
            : s,
          16
        );
        return {
          r: (bigint >> 16) & 255,
          g: (bigint >> 8) & 255,
          b: bigint & 255,
        };
      };
      const drawText = (el, yOffsetPx = 0) => {
        const text = el.props?.text ?? "";
        const sizePx = el.props?.fontSize ?? 14;
        const color = hexToRgb(el.props?.fill || "#111827");
        const bold = el.props?.fontStyle?.includes("bold");
        const align = el.props?.align || "left";
        const font = "Inter"; // falls back to default if not embedded
        try {
          doc.setFont(font, bold ? "bold" : "normal");
        } catch (_) {
          /* fallback to default */
        }
        // setFontSize expects points; scale px->pt preserving vertical mapping
        const sizePt = sizePx * (pageHeightPt / height);
        doc.setFontSize(sizePt);
        doc.setTextColor(color.r, color.g, color.b);

        // jsPDF text uses baseline; y + size approximates top alignment
        const xMm = (el.x || 0) * sx;
        const yMm =
          ((el.y || 0) - yOffsetPx) * sy +
          sizePt * (pageHeightMm / pageHeightPt);
        const maxWidthMm = el.width ? el.width * sx : pageWidthMm - xMm;
        const lines = String(text).split("\n");
        let cy = yMm;
        lines.forEach((line) => {
          // Split if line too long
          const chunks = doc.splitTextToSize(line, maxWidthMm);
          chunks.forEach((chunk) => {
            doc.text(chunk, xMm, cy, { align });
            cy += sizePt * (pageHeightMm / pageHeightPt) * 1.2;
          });
        });
      };
      const drawRect = (el, yOffsetPx = 0) => {
        const { r, g, b } = hexToRgb(el.props?.fill || "#000000");
        doc.setFillColor(r, g, b);
        const rad = el.props?.radius ?? 0;
        const xMm = (el.x || 0) * sx;
        const yMm = ((el.y || 0) - yOffsetPx) * sy;
        const wMm = (el.width || 0) * sx;
        const hMm = (el.height || 0) * sy;
        if (rad > 0) {
          const rMm = rad * ((sx + sy) / 2);
          doc.roundedRect(xMm, yMm, wMm, hMm, rMm, rMm, "F");
        } else {
          doc.rect(xMm, yMm, wMm, hMm, "F");
        }
      };
      const drawImage = (el, yOffsetPx = 0) => {
        const src = el.props?.src;
        if (!src) return;
        // jsPDF infers format from dataURL; keep aspect via fit in box
        try {
          const xMm = (el.x || 0) * sx;
          const yMm = ((el.y || 0) - yOffsetPx) * sy;
          const wMm = (el.width || 0) * sx;
          const hMm = (el.height || 0) * sy;
          doc.addImage(src, "PNG", xMm, yMm, wMm, hMm);
        } catch (_) {
          // ignore broken images
        }
      };

      // Multi-page: group elements by page index
      const pageHeight = height;
      const ordered = [...elements]
        .filter((e) => e.type !== "group")
        .sort((a, b) => (a.z || 0) - (b.z || 0));
      const byPage = new Map();
      ordered.forEach((el) => {
        const pageIndex = Math.max(0, Math.floor((el.y || 0) / pageHeight));
        if (!byPage.has(pageIndex)) byPage.set(pageIndex, []);
        byPage.get(pageIndex).push(el);
      });

      const maxPageIndex = byPage.size ? Math.max(...byPage.keys()) : 0;
      for (let p = 0; p <= maxPageIndex; p++) {
        if (p > 0) doc.addPage("a4", "portrait");
        const yOffsetPx = p * pageHeight;
        const pageEls = byPage.get(p) || [];
        pageEls.forEach((el) => {
          switch (el.type) {
            case "text":
              drawText(el, yOffsetPx);
              break;
            case "rect":
              drawRect(el, yOffsetPx);
              break;
            case "image":
              drawImage(el, yOffsetPx);
              break;
            default:
              break;
          }
        });
      }

      doc.save("resume.pdf");
    }

    return {
      canvas,
      setZoom,
      toggleGrid,
      toggleSnap,
      toggleGuides,
      cycleSnapSize,
      onAddText,
      onAddRect,
      onAddImage,
      onBringForward,
      onSendBackward,
      onDelete,
      centerX,
      centerY,
      duplicateSelected,
      previewResume,
      exportPdf,
    };
  },
};
</script>

<!-- stylelint-disable -->
<style scoped>
.btn {
  @apply px-3 py-1.5 rounded border border-gray-300 text-sm hover:bg-gray-50;
}
.btn-primary {
  @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
}
.btn-success {
  @apply bg-green-600 text-white border-green-600 hover:bg-green-700;
}
</style>
