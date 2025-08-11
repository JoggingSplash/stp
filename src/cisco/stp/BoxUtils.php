<?php

namespace cisco\stp;

use pocketmine\block\Air;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;

final class BoxUtils {

    /**
     * Checks if a Vector3 is inside to an AxisAligned object
     * Since {@link AxisAlignedBB::isVectorInside()} has 1 block of error
     * @param Vector3 $vec
     * @param AxisAlignedBB $aabb
     * @return bool
     */
    public static function isVectorInside(Vector3 $vec, AxisAlignedBB $aabb): bool {
        return (
            $vec->x >= $aabb->minX && $vec->x <= $aabb->maxX &&
            $vec->y >= $aabb->minY && $vec->y <= $aabb->maxY &&
            $vec->z >= $aabb->minZ && $vec->z <= $aabb->maxZ
        );
    }

    /**
     * @param AxisAlignedBB $bb
     * @return array{0: Vector3, 1: Vector3}
     */
    public static function getCorners(AxisAlignedBB $bb): array    {
        return [
            new Vector3($bb->minX, $bb->minY, $bb->minZ),
            new Vector3($bb->maxX, $bb->maxY, $bb->maxZ),
        ];
    }

    /**
     * Check All blocks surrounding the AxisAligned (brutal force)
     *
     * @param AxisAlignedBB $AABB
     * @param World $world
     * @param float $epsilonXZ
     * @param float $epsilonY
     * @return array
     */
    public static function checkBlocksInAABB(AxisAlignedBB $AABB, World $world, float $epsilonXZ = 1, float $epsilonY = 1) : \Generator {
        $minX = floor($AABB->minX - 1);
        $maxX = ceil($AABB->maxX + 1);
        $minY = floor($AABB->minY - 1);
        $maxY = ceil($AABB->maxY + 1);
        $minZ = floor($AABB->minZ - 1);
        $maxZ = ceil($AABB->maxZ + 1);

        for($x = $minX; $x <= $maxX; $x += $epsilonXZ){
              for($y = $minY; $y <= $maxY; $y += $epsilonY){
                  for($z = $minZ; $z <= $maxZ; $z += $epsilonXZ){
					  $block = $world->getBlockAt((int) $x, (int) $y, (int) $z, addToCache: false);

					  if(!$block instanceof Air){
						  yield $block;
					  }
				  }
              }
        }
	}

    /**
     * Creates an AABB for the current player chunk
     * @param int $chunkX
     * @param int $chunkZ
     * @return AxisAlignedBB
     */
    static public function getChunkBoundingBox(int $chunkX, int $chunkZ): AxisAlignedBB {
        $minX = $chunkX << 4;
        $minZ = $chunkZ << 4;
        $maxX = $minX + 16;
        $maxZ = $minZ + 16;

        return new AxisAlignedBB(
            $minX, 20, $minZ,
            $maxX, 100, $maxZ
        );
    }

    /**
     * @param int $chunkX
     * @param int $chunkZ
     * @return array{0: Vector3, 1: Vector3}
     */
    static public function getChunkCorners(int $chunkX, int $chunkZ): array{
        return self::getCorners(self::getChunkBoundingBox($chunkX, $chunkZ));
    }

    /**
     * @param Vector3 $vec
     * @return array{x: int, z: int}
     */
    static public function getChunkXZ(Vector3 $vec): array    {
        return  [
            "x" => $vec->x >> Chunk::COORD_BIT_SIZE,
            "z" => $vec->z >> Chunk::COORD_BIT_SIZE,
        ];
    }

}