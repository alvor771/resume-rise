import CompanyCard from './CompanyCard.vue';
import CandidateCard from './CandidateCard.vue';
import SwipePanel from './SwipePanel.vue';
import CultureMatch from './CultureMatch.vue';

// Экспортируем компоненты по отдельности
export { CompanyCard, CandidateCard, SwipePanel, CultureMatch };

// Экспортируем плагин для глобальной регистрации компонентов
const CvSwapComponents = {
  install(app) {
    // Регистрируем компоненты глобально
    app.component('CompanyCard', CompanyCard);
    app.component('CandidateCard', CandidateCard);
    app.component('SwipePanel', SwipePanel);
    app.component('CultureMatch', CultureMatch);
  },
};

export default CvSwapComponents;