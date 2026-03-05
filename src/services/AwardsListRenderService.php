<?php

class AwardsListRenderService 
{
    private readonly IAwardsRepository $awardsRepository;
    
    public function __construct(IAwardsRepository $awardsRepository)
    {
        $this->awardsRepository = $awardsRepository;
    }

    private array $material_list = [
        0 => "Pewter",
        1 => "Iron",
        2 => "Alloy",
        3 => "Copper",
        4 => "Bronze",
        5 => "Silver",
        6 => "Gold",
        7 => "Platinum"
    ];

    public function renderAwardsList(array $awards, int $size, string $tag = 'div'): void
    {
        $material_list = $this->getMaterialList();
        $html = "";
        foreach ($awards as $award) {
            $award['material_name'] = $material_list[$award['material']];
            $imageUrl = '/awards/medals/px' . $size . '/' . $award['style'] . $award['material'] . $award['color'] . $award['icon'] . '.gif';
            
            $specialClass = ($award['material'] == 7 || $award['style'] == 7) ? ' special_1' : '';
            $html .= '<div id="award_' . $award['id'] . '" class="award award_' . $size . $specialClass . '">';
            
            if ($tag === 'img') {
                $html .= '<img src="' . $imageUrl . '" width="' . $size . '" height="' . $size . '" alt="award" />';
            } else {
                $html .= '<div class="layer shine"></div>';
                $html .= '<div class="layer_mini" style="background-image: url(\'' . $imageUrl . '\');"></div>';
            }
            
            $html .= '<dl class="plaque">';
            $html .= '<dt>Level ' . $award['level'] . '<br /> ' . $award['material_name'] . ' ' . $award['category'] . ' Award:</dt>';
            $html .= '<dd>' . htmlspecialchars($award['message']) . '</dd>';
            $html .= '<dd class="award_cite">from <a href="../members/index.php?u=' . $award['username'] . '">' . $award['username'] . '</a></dd>';
            $html .= '</dl>';
            $html .= '</div>';
        }
        echo $html;
    }

    public function renderAwardsListWithPagination(array $awards, int $offset, int $perPage, int $total, string $tag = 'div'): void
    {
        $this->renderAwardsList($awards, 64, $tag, 'div');
        echo '<div class="spacer">&nbsp;</div><br><br>';
        echo '<div style="margin-right:90px;">';
        addPagination($total, $perPage, $offset);
        echo '</div>';
    }

    public function getMaterialList(): array
    {
        return $this->material_list;
    }
}
