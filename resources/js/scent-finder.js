export default function scentFinder() {
    return {
        step: 1,
        selectedMood: null,
        moods: [
            { id: 1, name: 'Relaxed', emoji: '😌', scents: ['Lavender', 'Chamomile'] },
            { id: 2, name: 'Energized', emoji: '⚡', scents: ['Peppermint', 'Citrus'] },
            { id: 3, name: 'Focused', emoji: '🎯', scents: ['Rosemary', 'Basil'] },
            { id: 4, name: 'Peaceful', emoji: '🕊️', scents: ['Vanilla', 'Sandalwood'] },
            { id: 5, name: 'Uplifted', emoji: '🌟', scents: ['Bergamot', 'Ylang-Ylang'] },
            { id: 6, name: 'Balanced', emoji: '⚖️', scents: ['Frankincense', 'Cedar'] },
        ],
        recommendations: [],
        
        selectMood(mood) {
            this.selectedMood = mood;
            this.generateRecommendations();
            setTimeout(() => this.step = 2, 300);
        },
        
        generateRecommendations() {
            // Algorithm to match scents based on mood
            this.recommendations = this.selectedMood.scents.map(scent => ({
                name: scent,
                description: this.getScentDescription(scent),
                image: `/images/products/${scent.toLowerCase()}.jpg`
            }));
        },
        
        getScentDescription(scent) {
            // Scent descriptions database
            const descriptions = {
                'Lavender': 'Calming and soothing properties that promote relaxation',
                'Peppermint': 'Invigorating aroma that boosts energy and clarity',
                // Add more descriptions
            };
            return descriptions[scent] || '';
        }
    }
}
