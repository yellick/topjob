class UrlCheck {
    /**
     * Проверяет текущий URL на наличие запрещенных подстрок
     * @param {string[]} forbiddenSubstrings - Массив подстрок для проверки
     * @returns {boolean} - true если ни одна подстрока не найдена, false если найдена хотя бы одна
     */
    static checkCurrentUrl(forbiddenSubstrings) {
      if (!Array.isArray(forbiddenSubstrings)) {
        throw new Error('Аргумент должен быть массивом строк');
      }
      
      const currentUrl = window.location.href.toLowerCase();
      return !forbiddenSubstrings.some(substring => {
        if (typeof substring !== 'string') return false;
        return currentUrl.includes(substring.toLowerCase());
      });
    }
  }