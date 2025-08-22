import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useBanners() {
    const page = usePage()
    
    const getBanner = (pagina, posicao) => {
        const banners = page.props.banners || []
        
        return banners.find(banner => 
            banner.ativo && 
            banner.pagina === pagina && 
            banner.posicao === posicao
        )
    }
    
    const getBannersByPage = (pagina) => {
        const banners = page.props.banners || []
        
        return banners.filter(banner => 
            banner.ativo && 
            banner.pagina === pagina
        ).sort((a, b) => a.ordem - b.ordem)
    }
    
    const getBannersByPosition = (pagina, posicao) => {
        const banners = page.props.banners || []
        
        return banners.filter(banner => 
            banner.ativo && 
            banner.pagina === pagina && 
            banner.posicao === posicao
        ).sort((a, b) => a.ordem - b.ordem)
    }
    
    return {
        getBanner,
        getBannersByPage,
        getBannersByPosition
    }
}
