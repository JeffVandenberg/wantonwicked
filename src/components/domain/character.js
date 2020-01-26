export default {
    getIdOrSlug(character) {
        return character.slug || character.id;
    }
}